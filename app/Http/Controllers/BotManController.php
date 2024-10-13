<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use Illuminate\Http\Request;

class BotManController extends Controller
{
    /**
     * Handle incoming BotMan messages.
     */
    public function handle()
    {
        $botman = app('botman');

        // Define multiple questions and answers
        $botman->hears('{message}', function ($botman, $message) {
            switch (strtolower($message)) {
                case 'hi':
                    $this->askName($botman);
                    break;
                case 'what if there is an emergency and i need to be absent?':
                    $botman->reply("It is okay to be absent as long as you have completed the required number of hours for the OJT.");
                    break;
                case 'is there an approved journal for the internship?':
                    $botman->reply("There is no journal approved for the internship, so it is of no concern to us.");
                    break;
                case 'do they provide an allowance during the internship program?':
                    $botman->reply("Some companies provide allowances, but students may need to undergo screening, which includes an exam to assess their qualifications.");
                    break;
                case 'what if i no longer have money to pay for my allowance, especially those assigned in manila?':
                    $botman->reply("You should ensure that you have enough money for your expenses before going to Manila. There is no need to back out, but proper planning is essential.");
                    break;
                case 'are there any other questions?':
                    $botman->reply("All concerns will be addressed during a meeting with your respective SIP faculty.");
                    break;
                default:
                    // Show common question buttons if no match is found
                    $this->showCommonQuestions($botman);
                    break;
            }
        });

        $botman->listen();
    }

    /**
     * Show common question buttons if no match is found.
     */
    public function showCommonQuestions($botman)
    {
        $question = Question::create("Sorry, I did not understand. Here are some common questions you can ask:")
            ->addButtons([
                Button::create('What if there is an emergency and I need to be absent?')->value('what if there is an emergency and i need to be absent?'),
                Button::create('Is there an approved journal for the internship?')->value('is there an approved journal for the internship?'),
                Button::create('Do they provide an allowance during the internship program?')->value('do they provide an allowance during the internship program?'),
                Button::create('What if I no longer have money for my allowance in Manila?')->value('what if i no longer have money to pay for my allowance, especially those assigned in manila?'),
                Button::create('Are there any other questions?')->value('are there any other questions?'),
            ]);

        $botman->reply($question);
    }

    /**
     * Ask for the user's name.
     */
    public function askName($botman)
    {
        $botman->ask('Hello! What is your name?', function ($answer) {
            $name = $answer->getText();
            $this->say('Nice to meet you, ' . $name . '!');
        });
    }
}
