<?php

namespace NotificationChannels\SMS77\Test;

use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\SMS77\SMS77;
use NotificationChannels\SMS77\SMS77Channel;
use NotificationChannels\SMS77\SMS77Message;
use PHPUnit\Framework\TestCase;

class SMS77ChannelTest extends TestCase
{
    /**
     * @var Mockery\Mock
     */
    protected $sms77;

    /**
     * @var SMS77
     */
    protected $channel;

    /**
     * @var array
     */
    protected $expectedResponse = [
        'success' => '100',
        'debug' => 'true',
        'sms_type' => 'direct',
        'messages' => [
            'id' => 123,
            'sender' => 'SMS',
            'text' => 'This is my message.',
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->sms77 = Mockery::mock(SMS77::class);
        $this->channel = new SMS77Channel($this->sms77);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testSmsIsSent()
    {
        $notification = new TestSmsNotification;
        $notifiable = new TestNotifiable;

        $this->sms77->shouldReceive('sendMessage')->once()->with([
            'json' => 1,
            'text' => 'This is my message.',
            'to' => '5555555555',
        ])
            ->andReturns(new Response(200, [], json_encode($this->expectedResponse)));

        $actualResponse = $this->channel->send($notifiable, $notification);

        self::assertSame($this->expectedResponse, $actualResponse);
    }

    public function testSmsIsSentWithCustomFrom()
    {
        $notification = new TestSmsNotificationWithCustomFrom;
        $notifiable = new TestNotifiable;

        $this->sms77->shouldReceive('sendMessage')->once()
            ->with([
                'json' => 1,
                'from' => '5554443333',
                'to' => '5555555555',
                'text' => 'This is my message.',
            ])
            ->andReturns(new Response(200, [], json_encode($this->expectedResponse)));

        $actualResponse = $this->channel->send($notifiable, $notification);

        self::assertSame($this->expectedResponse, $actualResponse);
    }

    public function testSmsSendWithAllMessageOptions()
    {
        $notification = new TestSmsNotificationWithAllMessageOptions;
        $notifiable = new TestNotifiable;

        $this->sms77->shouldReceive('sendMessage')->once()
            ->with([
                'from' => '987654321',
                'to' => '123456789',
                'text' => 'This is my message.',
                'delay' => '000000',
                'flash' => 1,
                'details' => 1,
                'json' => 1,
            ])
            ->andReturns(new Response(200, [], json_encode($this->expectedResponse)));

        $actualResponse = $this->channel->send($notifiable, $notification);

        self::assertSame($this->expectedResponse, $actualResponse);
    }
}

class TestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';

    public function routeNotificationForSms($notification)
    {
        return $this->phone_number;
    }
}

/**
 * Default notification.
 */
class TestSmsNotification extends Notification
{
    public function toSms77($notifiable)
    {
        return new SMS77Message('This is my message.');
    }
}

/**
 * Notification with custom sender.
 */
class TestSmsNotificationWithCustomFrom extends Notification
{
    public function toSms77($notifiable)
    {
        return (new SMS77Message('This is my message.'))->from('5554443333');
    }
}

/**
 * Notification with custom sender.
 */
class TestSmsNotificationWithDebiggung extends Notification
{
    public function toSms77($notifiable)
    {
        return (new SMS77Message('This is my message.'))->from('5554443333');
    }
}

/**
 * Notification with all available options.
 */
class TestSmsNotificationWithAllMessageOptions extends Notification
{
    public function toSms77($notifiable)
    {
        return (new SMS77Message('This is my message.'))
            ->to('123456789')
            ->from('987654321')
            ->delay('00000000')
            ->flash()
            ->details();
    }
}
