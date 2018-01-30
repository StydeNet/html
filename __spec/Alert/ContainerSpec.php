<?php

namespace spec\Styde\Html\Alert;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Styde\Html\Alert\Handler;
use Styde\Html\Alert\Message;
use Styde\Html\Theme;
use Illuminate\Session\Store as Session;
use Illuminate\Translation\Translator as Lang;

class ContainerSpec extends ObjectBehavior
{

    public function let(Handler $handler, Theme $theme)
    {
        $this->beConstructedWith($handler, $theme);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\Alert\Container');
    }

    function it_creates_new_messages()
    {
        $this->message('This is a message', 'info')
            // Except
            ->shouldReturnAnInstanceOf(Message::class);
    }

    function it_retrieves_new_messages($handler)
    {
        // When
        $handler->getPreviousMessages()->shouldBeCalled()->willReturn([]);

        $this->message('This is a message', 'info');

        // Expect
        $messages = [
            [
                'message' => "This is a message",
                'type' => 'info',
            ],
        ];
        $this->toArray()->shouldReturn($messages);
    }

    function it_uses_magic_methods($handler)
    {
        $handler->getPreviousMessages()->willReturn([]);

        // When - Expect
        $this->success('Success!')->shouldReturnAnInstanceOf('Styde\Html\Alert\Message');
        $this->info('Some information')->shouldReturnAnInstanceOf('Styde\Html\Alert\Message');

        $messages = [
            ['message' => "Success!", 'type' => 'success'],
            ['message' => 'Some information', 'type' => 'info']
        ];
        $this->toArray()->shouldReturn($messages);
    }

    function it_translate_the_messages($handler, Lang $lang)
    {
        // Having
        $key = 'message.key';
        $type = 'success';
        $translation = 'Message translated';

        $this->setLang($lang);

        // Expect
        $lang->get($key, [])->shouldBeCalled()->willReturn($translation);
        $handler->getPreviousMessages()->willReturn([]);

        // When
        $this->message($key, $type);

        $messages = [
            ['message' => $translation, 'type' => $type],
        ];
        $this->toArray()->shouldReturn($messages);
    }

    function it_chains_methods_to_build_complex_alert_messages($handler)
    {
        // Having
        $message = 'Your account is about to expire';
        $details = 'A lot of knowledge still waits for you:';
        $items = [
            'Laravel courses',
            'OOP classes',
            'Access to real projects',
            'Support',
            'And more'
        ];
        $button = ['text' => 'Renew now!', 'url' => '#', 'class' => 'primary'];
        $button2 = ['text' => 'Take me to your leader', 'url' => 'http://google.com', 'class' => 'info'];

        $handler->getPreviousMessages()->willReturn([]);

        // When
        $this->info($message)
            ->details($details)
            ->items($items)
            ->button($button['text'], $button['url'], $button['class'])
            ->button($button2['text'], $button2['url'], $button2['class']);

        // Expect
        $messages = [
            [
                'message' => $message,
                'type'    => 'info',
                'details' => $details,
                'items'   => $items,
                'buttons' => [$button, $button2]
            ],
        ];
        $this->toArray()->shouldReturn($messages);
    }

    function it_render_the_messages($handler, $theme)
    {
        // Having
        $message = "Success!";
        $type = "success";

        $messages = [
            [
                "details" => "",
                "html" => "",
                "list" => [],
                "buttons" => [],
                "message" => $message,
                "type" => $type
            ]
        ];

        $handler->getPreviousMessages()->willReturn([
            compact('message', 'type')
        ]);

        // Expect
        $handler->clean()->shouldBeCalled();
        $theme->render(null, compact('messages'), "alert")->shouldBeCalled();

        // When
        $this->render();
    }
}