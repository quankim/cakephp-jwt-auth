<?php
/**
 * Created by PhpStorm.
 * User: QuânKim
 * Date: 7/4/2016
 * Time: 12:55 AM
 */

namespace QuanKim\JwtAuth\Controller;


use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use QuanKim\PhpJwt\JWT;

class UsersController extends AppController
{
    public function token(){
        if ($this->request->is('post')){
            $table = TableRegistry::get('AuthToken');
            $refresh_token = $this->request->data('refresh_token');
            $authToken = $table->find('all')->where(['refresh_token'=>$refresh_token])->first();
            if ($authToken) {
                $expire =  (!is_null(Configure::read('AuthToken.expire'))) ? Configure::read('AuthToken.expire') : 3600;
                $access_token = JWT::encode([
                    'sub' => $authToken['user_id'],
                    'exp' =>  time() + $expire
                ],Security::salt());
                $refresh_token = JWT::encode([
                    'sub' => $authToken['user_id'],
                    'ref'=>time()
                ],Security::salt());
                $authToken->access_token = $access_token;
                $authToken->refresh_token = $refresh_token;
                $table->save($authToken);
                $this->set([
                    'success'=>true,
                    'data'=>[
                        'access_token'=>$access_token,
                        'refresh_token'=>$refresh_token
                    ],
                    '_serialize' => ['success', 'data']
                ]);
            } else {
                $this->set([
                    'success'=>false,
                    'refresh_token_expired'=>true,
                    '_serialize' => ['success','refresh_token_expired']
                ]);
            }
        }

    }
}