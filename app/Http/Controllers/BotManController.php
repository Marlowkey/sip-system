<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use Illuminate\Http\Request;

class BotManController extends Controller
{

    public function handle()
    {
        $botman = app('botman');

        $botman->hears('{message}', function ($botman, $message) {
            $questions = [
                'what if there is an emergency and i need to be absent?' => "It is okay to be absent as long as you have completed the required number of hours for the OJT.",
                'is there an approved journal for the internship?' => "There is no journal approved for the internship, so it is of no concern to us.",
                'do they provide an allowance during the internship program?' => "Some companies provide allowances, but students may need to undergo screening, which includes an exam to assess their qualifications.",
                'what if i no longer have money to pay for my allowance, especially those assigned in manila?' => "You should ensure that you have enough money for your expenses before going to Manila. There is no need to back out, but proper planning is essential.",
                'are there any other questions?' => "All concerns will be addressed during a meeting with your respective SIP faculty.",
                'who are the sip coordinators?' => "For Computer Science, the SIP coordinator is Sir Arnold. For Information Technology, it is Ma'am Merlijoy. For Information Systems, it is Ma'am Ste.",
                'which companies can we intern for?' => "Companies can be local or located in other cities. Common questions to ask the company when applying are: Are they available for the internship program? How many students can they accept? What tasks are available to be done? Who will be the signatory for the MOA?",
                'what are the assessments for cs and is students?' => "Students in CS and IS will take assessments. If passed, they are not required to take the MOS certification. If they fail, they will need to take the MOS certification or two other certifications related to their field.",
                'what is the criteria for it students?' => "The General Weighted Average (GWA) will be the criteria for IT students.",
                'how many hours are required for it students?' => "IT students are required to complete 486 hours for their internship. The GAD and alumni seminar will be deducted from these hours.",
                'what if the required number of hours is not completed?' => "The student will not be able to complete the Internship program.",
                'can someone fail ojt?' => "Yes, if the assessment given by the company failed.",
                'what if ojt is completed earlier than expected?' => "It is better. As long as the number of hours is satisfied, it is better.",
                'can you extend ojt beyond the required number of hours?' => "Yes, but the school is no longer responsible for what will happen to the student.",
                'can someone back out in the middle of ojt?' => "There is no way to back out in the middle of the OJT, but if an emergency occurs, it will be discussed with the concerned parties.",
            ];

            $messageLower = strtolower($message);

            if (array_key_exists($messageLower, $questions)) {
                $botman->reply($questions[$messageLower]);
            } else {
                $matchedQuestion = $this->findClosestQuestions($message, array_keys($questions));

                if ($matchedQuestion) {
                    $this->showDidYouMean($botman, $matchedQuestion);
                } else {
                    $this->showCommonQuestions($botman);
                }
            }
        });

        $botman->listen();
    }
    public function findClosestQuestions($input, $questions)
    {
        $inputLower = strtolower($input);
        $inputWords = explode(' ', $inputLower);
        $matchedQuestions = [];

        foreach ($questions as $question) {
            $questionLower = strtolower($question);
            $questionWords = explode(' ', $questionLower);

            $commonWords = array_intersect($inputWords, $questionWords);

            if (count($commonWords) > 0) {
                $matchedQuestions[] = $question;
            }
        }

        return $matchedQuestions;
    }

    public function showDidYouMean($botman, $matchedQuestions)
    {
        if (empty($matchedQuestions)) {
            return;
        }

        $questionText = "Did you mean:";
        $buttons = [];

        foreach ($matchedQuestions as $question) {
            $buttons[] = Button::create(ucfirst($question))->value($question);
        }

        $question = Question::create($questionText)->addButtons($buttons);
        $botman->reply($question);
    }

    public function showCommonQuestions($botman)
    {
        $question = Question::create("Sorry, I did not understand. Here are some common questions you can ask:")
            ->addButtons([
                Button::create('Who are the SIP coordinators?')->value('who are the sip coordinators?'),
                Button::create('Which companies can we intern for?')->value('which companies can we intern for?'),
                Button::create('What are the assessments for CS and IS students?')->value('what are the assessments for cs and is students?'),
                Button::create('What is the criteria for IT students?')->value('what is the criteria for it students?'),
                Button::create('How many hours are required for IT students?')->value('how many hours are required for it students?'),
                Button::create('Are there any other questions?')->value('are there any other questions?'),
            ]);

        $botman->reply($question);
    }



    public function handleQuestion($botman, $question)
    {
        switch (strtolower($question)) {
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
            case 'who are the sip coordinators?':
                $botman->reply("For Computer Science, the SIP coordinator is Sir Arnold. For Information Technology, it is Ma'am Merlijoy. For Information Systems, it is Ma'am Ste.");
                break;
            case 'which companies can we intern for?':
                $botman->reply("Companies can be local or located in other cities. Common questions to ask the company when applying are: Are they available for the internship program? How many students can they accept? What tasks are available to be done? Who will be the signatory for the MOA?");
                break;
            case 'what are the assessments for cs and is students?':
                $botman->reply("Students in CS and IS will take assessments. If passed, they are not required to take the MOS certification. If they fail, they will need to take the MOS certification or two other certifications related to their field.");
                break;
            case 'what is the criteria for it students?':
                $botman->reply("The General Weighted Average (GWA) will be the criteria for IT students.");
                break;
            case 'how many hours are required for it students?':
                $botman->reply("IT students are required to complete 486 hours for their internship. The GAD and alumni seminar will be deducted from these hours.");
                break;
            case 'what if the required number of hours is not completed?':
                $botman->reply("The student will not be able to complete the Internship program.");
                break;
            case 'can someone fail ojt?':
                $botman->reply("Yes, if the assessment given by the company failed.");
                break;
            case 'what if ojt is completed earlier than expected?':
                $botman->reply("It is better. As long as the number of hours is satisfied, it is better.");
                break;
            case 'can you extend ojt beyond the required number of hours?':
                $botman->reply("Yes, but the school is no longer responsible for what will happen to the student.");
                break;
            case 'can someone back out in the middle of ojt?':
                $botman->reply("There is no way to back out in the middle of the OJT, but if an emergency occurs, it will be discussed with the concerned parties.");
                break;
        }
    }
}
