<?php

namespace App\Classes;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private string $api_key = '9f8ec4e6eddfe80e668714cb9fbe5545';
    private string $api_key_secret = '547f58d2cf190109f6cce0c40581e38e';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "no-reply@generation-boomerang.com",
                        'Name' => "Generation Boomerang"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3422480,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}