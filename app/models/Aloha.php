<?php

class Aloha extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'users';

    /**
     * Stopping automatically inserting updated_at/created_at
     *
     * @var boolean
     */
    public $timestamps  = false;

    public function scopeGetUserById($query, $userId)
    {
        $query  = DB::connection('schoolerUsers')->table('users');
        
        $res    = $query->select('*')->where('userId', $userId);
    
        return $res;
    }
    
    public function scopeGetUserByEmail($query, $email)
    {
        $query  = DB::connection('schoolerUsers')->table('users');
        
        $res    = $query->select('*')->where('email', $email);
    
        return $res;
    }
    
    public function scopeCheckAnyVerificationCodeByEmail($query, $email)
    {
        $query    = DB::connection('schoolerUsers')->table('forgotPasswordVerify');
        
        $res      = $query->select('*')->where('email', $email);
        
        return $res;
    }
    
    public function scopeGetForgotPasswordVerifyCodeInfo($query, $code)
    {
        $query    = DB::connection('schoolerUsers')->table('forgotPasswordVerify');
    
        $res      = $query->select('*')->where('code', $code);
    
        return $res;
    }
    
    public function scopeGetVerifyCodeInfo($query, $code)
    {
        $query    = DB::connection('schoolerUsers')->table('verify');
        
        $res      = $query->select('*')->where('code', $code);
        
        return $res;
    }
    
    public function scopeCreateUserId($query, $postData, $code)
    {
        $query  = DB::connection('schoolerUsers')->table('users');
        $now    = date('Y-m-d H:i:s');
    
        $dataForUsers = array(
            'email'             => $postData['email'],
            'password'          => Hash::make($postData['password']),
            'userType'          => 1,
            'userStatus'        => 0, // not verified yet
            'remember_token'    => '',
            'createdBy'         => 0, // 0-> system
            'updatedBy'         => 0, // 0-> system
            'dateCreated'       => $now,
            'dateUpdated'       => $now
        );
    
        $insertId = $query->insertGetId($dataForUsers);
        
        $query  = DB::connection('schoolerUsers')->table('verify');
        $dataForVerify = array(
            'userId'            => $insertId,
            'email'             => $postData['email'],
            'code'              => $code,
            'createdBy'         => 0, // 0-> system
            'updatedBy'         => 0, // 0-> system
            'dateCreated'       => $now,
            'dateUpdated'       => $now
        );
        
        $query->insert($dataForVerify);
    
        return $insertId;
    }

    public function scopeCompleteUserSignup($query, $userId, $email, $code)
    {
        $query  = DB::connection('schoolerUsers')->table('verify');
        $now    = date('Y-m-d H:i:s');
    
        $dataForVerify = array(
            'code'              => null,
            'updatedBy'         => 0, // 0-> system
            'dateUpdated'       => $now
        );
    
        $query->where('userId', $userId)
        ->where('email', $email)
        ->update($dataForVerify);
        
        $query  = DB::connection('schoolerUsers')->table('users');
        
        $dataForUsers = array(
            'userStatus'        => 1,
            'updatedBy'         => 0, // 0-> system
            'dateUpdated'       => $now
        );
        
        $query->where('userId', $userId)
        ->where('email', $email)
        ->update($dataForUsers);
    }
    
    public function scopeInsertPasswordVerificationCode($query, $userId, $email, $code)
    {
        $query  = DB::connection('schoolerUsers')->table('forgotPasswordVerify');
        $now    = date('Y-m-d H:i:s');
        
        $dataForPasswordVerify = array(
            'userId'            => $userId,
            'email'             => $email,
            'code'              => $code,
            'createdBy'         => 0, // 0-> system
            'updatedBy'         => 0, // 0-> system
            'dateCreated'       => $now,
            'dateUpdated'       => $now
        );
        
        $query->insert($dataForPasswordVerify);
    }
        
    public function scopeUpdatePasswordVerificationCode($query, $userId, $email, $code)
    {
        $query  = DB::connection('schoolerUsers')->table('forgotPasswordVerify');
        $now    = date('Y-m-d H:i:s');
    
        $dataForPasswordVerify = array(
            'code'              => $code,
            'updatedBy'         => 0, // 0-> system
            'dateUpdated'       => $now
        );
    
        $query->where('userId', $userId)
        ->where('email', $email)
        ->update($dataForPasswordVerify);
    }
    
    public function scopeForgotPasswordUpdate($query, $userId, $email, $newPassword)
    {
        $query  = DB::connection('schoolerUsers')->table('users');
        $now    = date('Y-m-d H:i:s');
        
        $dataForPasswordUpdate = array(
            'password'          => $newPassword,
            'updatedBy'         => 0, // 0-> system
            'dateUpdated'       => $now
        );
        
        $query->where('userId', $userId)
        ->where('email', $email)
        ->update($dataForPasswordUpdate);
    }
}