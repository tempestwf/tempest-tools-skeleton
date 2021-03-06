<?php

use App\API\V1\Repositories\EmailVerificationRepository;
use App\API\V1\Repositories\UserRepository;
use App\API\V1\UnitTest\CrudTestBase;
use Faker\Factory;

class LoginAttemptTest extends CrudTestBase
{
    protected $password = 'Password00!';

    /**
     * @group registrationFlow
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws Exception
     */
    public function testEmailVerification():void
    {
        putenv('MAX_LOGIN_ATTEMPTS_BEFORE_PARTIAL_LOCK=3');
        putenv('MAX_LOGIN_ATTEMPTS_BEFORE_FULL_LOCK=3');
        putenv('LOGIN_PARTIAL_LOCK_TIMEOUT=1');
        putenv('LOGIN_FULL_LOCK_TIMEOUT=1');

        $em = $this->em();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            $generator = Factory::create();
            /** Register user via the guest endpoint **/
            $response = $this->json(
                'POST', '/contexts/guest/users',
                [
                    "params" => [
                        'email' => $generator->safeEmail,
                        'firstName'=> $generator->firstName,
                        'middleInitial'=>'X',
                        'lastName'=> $generator->lastName,
                        'age' => $generator->randomNumber(2),
                        'gender' => 1,
                        'weight' => 210,
                        'height' => 180.34,
                        'phoneNumber' => "+1 757-571-2711",
                        'lifestyle' => 1,
                        'password' => $this->password,
                        'job' => $generator->jobTitle,
                        'address' => $generator->address,
                        'locale' => "en"
                    ],
                    "options" => [
                        "email" => false,
                        "g-recaptcha-response-omit" => env('GOOGLE_RECAPTCHA_SKIP_CODE') /** skipping recaptcha with a key **/
                    ]
                ]
            );
            $userResult = $response->decodeResponseJson();
            $this->assertArrayHasKey('id', $userResult);

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
            $response->assertResponseStatus(200);

            /** Making sure user has user has role entry of 'user' **/
            $userRepository = new UserRepository();
            $user = $userRepository->find($userResult["id"]);
            $this->assertEquals( $user->getId(), $userResult['id']);
            $this->assertEquals( $user->getRoles()[0]->getName(), 'user');

            /** Try to log in with wrong password **/
            $response = $this->json('POST', '/auth/authenticate', ['email' => $userResult["email"], 'password' => 'wrong password']);
            $response->assertResponseStatus(401);

            /** Try to log in with wrong password **/
            $response = $this->json('POST', '/auth/authenticate', ['email' => $userResult["email"], 'password' => 'wrong password']);
            $response->assertResponseStatus(401);

            /** Try to log in with wrong password **/
            $response = $this->json('POST', '/auth/authenticate', ['email' => $userResult["email"], 'password' => 'wrong password']);
            $response->assertResponseStatus(403, env('MAX_LOGIN_ATTEMPTS_BEFORE_PARTIAL_LOCK') === 3 ? 403: 401);

            /** Leave no trace of test **/
            $conn->rollBack();
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}