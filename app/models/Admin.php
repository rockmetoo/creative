<?php

class Admin extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table      = 'users';
    
    /**
     * Stopping automatically inserting updated_at/created_at
     *
     * @var boolean
     */
    public $timestamps    = false;

    public function scopeCountTotalAdminData($query)
    {
        $query    = DB::connection('aloha')->table('users');
        
        return $query->selectRaw('COUNT(*) as count')->where('userType', 1);
    }

    public function scopeGetAdminDataByPage($query, $limit)
    {
        $query    = DB::connection('aloha');
        
        return $query->select(DB::raw("SELECT * FROM users WHERE userType=1 ORDER BY userId ASC $limit"));
    }
    
    public function scopeGetUserAcl($query, $userId)
    {
        $query    = DB::connection('aloha')->table('acl');
        
        $res      = $query->select('acl')->where('userId', $userId);
    
        return $res;
    }
    
    public function scopeInsertAdmin($query, $postData)
    {
        $now    = date('Y-m-d H:i:s');
        $userId = Auth::user()->userId;

        $dataForUsers = array(
            'email'            => $postData['email'],
            'password'         => Hash::make($postData['password']),
            'userType'         => 1,
            'remember_token'   => '',
            'createdBy'        => $userId,
            'updatedBy'        => $userId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        );
        
        $insertId = $query->insertGetId($dataForUsers);
        
        return $insertId;
    }
    
    public function scopeFreezeAdmin($query, $email)
    {
        $now    = date('Y-m-d H:i:s');
        $userId = Auth::user()->userId;

        $dataForUsers = array(
            'userStatus'    => 2,
            'updatedBy'     => $userId,
            'dateUpdated'   => $now
        );
    
        $query->where('email', $email)->update($dataForUsers);
    }

    public function scopeActiveAdmin($query, $email)
    {
        $now    = date('Y-m-d H:i:s');
        $userId = Auth::user()->userId;
    
        $dataForUsers = array(
            'userStatus'    => 1,
            'updatedBy'     => $userId,
            'dateUpdated'   => $now
        );
    
        $query->where('email', $email)->update($dataForUsers);
    }
    
    public function scopeUpdateUserPassword($query, $userId, $postData)
    {
        $now    = date('Y-m-d H:i:s');
        
        $dataForUsers = array(
            'password'      => Hash::make($postData['password']),
            'updatedBy'     => Auth::user()->userId,
            'dateUpdated'   => $now
        );
        
        $query->where('userId', $userId)->update($dataForUsers);
    }

    public function scopeUpdateUserAcl($query, $userId, $postData)
    {
        $query  = DB::connection('aloha')->table('acl');
        $now    = date('Y-m-d H:i:s');
    
        $dataForAcl = array(
            'acl'          => json_encode($postData['acl']),
            'updatedBy'    => Auth::user()->userId,
            'dateUpdated'  => $now
        );
        
        $query->where('userId', $userId)->update($dataForAcl);
    }
    
    public function scopeInsertJustUserIdInAcl($query, $userId)
    {
        $query  = DB::connection('aloha')->table('acl');
        $now    = date('Y-m-d H:i:s');
    
        $dataForAcl = array(
            'userId'        => $userId,
            'acl'           => '[]',
            'createdBy'     => Auth::user()->userId,
            'updatedBy'     => Auth::user()->userId,
            'dateCreated'   => $now,
            'dateUpdated'   => $now
        );
    
        $query->insert($dataForAcl);
    }
}
