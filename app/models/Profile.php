<?php

class Profile extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table       = 'profile';
    
    protected $primaryKey  = 'userId';

    /**
     * Stopping automatically inserting updated_at/created_at
     *
     * @var boolean
     */
    public $timestamps  = false;

    public function scopeGetProfile($query, $userId)
    {
        $res = $query->select('*')->where('userId', $userId);
    
        return $res;
    }
    
    public function scopeSaveProfile($query, $userId, $postData)
    {
        $now = date('Y-m-d H:i:s');
        
        $profilePictureFile = Input::file('profilePictureUpload');

        if ($profilePictureFile) {
            $md5Name = md5(Auth::user()->email);
            $img = Image::make(public_path().'/uploadFiles/tmpProfilePic/'.$md5Name);
            
            $img->resize(160, 160);
            
            $realPath = public_path().'/uploadFiles/realProfilePic/'.$md5Name.'_160X160.jpg';
            $img->save($realPath);
            
            $img = Image::make($realPath);
            $img->crop($postData['w'], $postData['h'], $postData['x'], $postData['y']);
            $img->save(public_path().'/uploadFiles/realProfilePic/'.$md5Name.'_croped.jpg');
            
            $img = Image::make(public_path().'/uploadFiles/realProfilePic/'.$md5Name.'_croped.jpg');
            $img->resize(64, 64);
            $img->save(public_path().'/uploadFiles/realProfilePic/'.$md5Name.'_64X64.jpg');
            
            $img = Image::make(public_path().'/uploadFiles/realProfilePic/'.$md5Name.'_croped.jpg');
            $img->resize(32, 32);
            $img->save(public_path().'/uploadFiles/realProfilePic/'.$md5Name.'_32X32.jpg');
            
            $dataForProfile = array(
                'firstName'   => $postData['firstName'],
                'lastName'    => $postData['lastName'],
                'postCode'    => $postData['postCode'],
                'address'     => $postData['address'],
                'p0'          => $md5Name.'_32X32.jpg',
                'p1'          => $md5Name.'_64X64.jpg',
                'p2'          => $md5Name.'_160X160.jpg',
                'dateUpdated' => $now
            );
        } else {
            $dataForProfile = array(
                'firstName'   => $postData['firstName'],
                'lastName'    => $postData['lastName'],
                'postCode'    => $postData['postCode'],
                'address'     => $postData['address'],
                'dateUpdated' => $now
            );
        }

        $query->where('userId', $userId)->update($dataForProfile);
    }
    
    public function scopeInsertJustUserId($query)
    {
        $now = date('Y-m-d H:i:s');
        
        $dataForProfile = array(
            'userId'    => Auth::user()->userId,
            'dateCreated' => $now,
            'dateUpdated' => $now
        );
        
        $query->insert($dataForProfile);
    }
    
    public function scopeUpdateTemporaryPicture($query, $pTmp)
    {
        $now = date('Y-m-d H:i:s');
        
        $dataForProfile = array(
            'pTmp'        => $pTmp,
            'dateUpdated' => $now
        );
        
        $query->where('userId', Auth::user()->userId)->update($dataForProfile);
    }
}
