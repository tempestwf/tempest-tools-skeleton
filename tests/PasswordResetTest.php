<?php
/**
 * Created by PhpStorm.
 * User: monxe
 * Date: 19/04/2018
 * Time: 8:31 PM
 */

use App\API\V1\Repositories\EmailVerificationRepository;
use Faker\Factory;
use TempestTools\Scribe\PHPUnit\CrudTestBaseAbstract;

class PasswordResetTest extends CrudTestBaseAbstract
{
    protected $password = '441520435a0a2dac143af05b55f4b751';


    /**
     * Test that acl middle ware works in allowing some one to access an end point
     * @group passwordReset
     * @return void
     * @throws Exception
     */
    public function testGuestAccess():void
    {
        $generator = Factory::create();
        $em = $this->em();
        //$conn = $em->getConnection();
        //$conn->beginTransaction();
        try {
            $email = "jerome.erazo@gmail.com";
            $password = $this->password;
            /** Register user via the guest endpoint **/
            $response = $this->json(
                'POST', '/contexts/guest/users',
                [
                    "params" => [
                        "name" => $generator->name,
                        "email" => $email,
                        "password" => $password,
                        "job" => $generator->jobTitle,
                        "address" => $generator->address,
                        "locale" => "en"
                    ],
                    "options" => [
                        "email" => false,
                        "g-recaptcha-response-omit" => env('GOOGLE_RECAPTCHA_SKIP_CODE') /** skipping recaptcha with a key **/
                    ]
                ]
            );
            $userResult = $response->decodeResponseJson();
            $this->assertArrayHasKey('id', $userResult);

            $this->refreshApplication();

            /** Test password reset with email not verified yet **/
            // TODO: If we want to make sure passwords can't be reset on unverified emails then we can put back in this test
            /*$response = $this->json(
                'POST', "/contexts/guest/password-reset",
                [
                    "params" => ["user" => ["id" => $userResult['id']]],
                    "options" => [ "simplifiedParams" => true ]
                ]
            );
            $response->assertResponseStatus(500);*/

            /** Making sure user has email verification entry **/
            $emailVerificationRepository = new EmailVerificationRepository();
            $emailVerification = $emailVerificationRepository->findOneBy(["user" => $userResult["id"]]);
            $this->assertEquals( $emailVerification->getUser()->getId(), $userResult['id']);
            $this->assertEquals( $emailVerification->getVerified(), false);

            /** Email verification endpoint **/
            $response = $this->json(
                'PUT', "/contexts/guest/email-verification/" . $emailVerification->getId(),
                [
                    "params" => [ "verified" => true ],
                    "options" => [ "simplifiedParams" => true ]
                ]
            );
            $result = $response->decodeResponseJson();
            $this->assertArrayHasKey('id', $result);


            $this->refreshApplication();

            /** Test password reset with email verified **/
            $response = $this->json(
                'POST', "/contexts/guest/password-reset",
                [
                    "params" => ["user" => ["id" => $userResult['id']]],
                    "options" => [ "simplifiedParams" => true ]
                ]
            );
            $result = $response->decodeResponseJson();
            $response->assertResponseStatus(201);

            $this->refreshApplication();

            /** Change password **/
            $response = $this->json(
                'PUT', '/contexts/guest/password-reset/' . $result['id'],
                [
                    "params" => ["verified" => true] ,
                    "options" => [
                        "password"=>"1ce51220016dbb4443e19a7ce308555f",
                        "simplifiedParams" => true
                    ]
                ]
            );
            $resultx = $response->decodeResponseJson();
            $response->assertResponseStatus(200);

            $this->refreshApplication();

            /** Change password again **/
            $response = $this->json(
                'PUT', '/contexts/guest/password-reset/' . $result['id'],
                [
                    "params" => ["verified" => true],
                    "options" => [
                        "password"=>"1ce51220016dbb4443e19a7ce308555f",
                        "simplifiedParams" => true
                    ]
                ]
            );
            $result = $response->decodeResponseJson();
            $response->assertResponseStatus(500);

            $this->refreshApplication();

            /** Test login validated **/
            $response = $this->json('POST', '/auth/authenticate', ['email' => $email, 'password' => '1ce51220016dbb4443e19a7ce308555f']);
            $tokenResult = $response->decodeResponseJson();
            $response->assertResponseStatus(200);
            $this->assertArrayHasKey('token', $tokenResult);

            //$conn->rollBack();
            $this->refreshApplication();
        } catch (Exception $e) {
            //$conn->rollBack();
            throw $e;
        }
    }
}