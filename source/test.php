<?php

require "Catapult.php";

try {
        $cred = new Catapult\Credentials("", "", "", "");
        $client = new Catapult\Client($cred);

        $baml1 = new Catapult\BaML;
        $baml1->parse("<Response>
        <SpeakSentence voice='male'>
            test
        <Record></Record>
        <Record></Record>
        </SpeakSentence>
    </Response>");

        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLVerbSpeakSentence;
        $verb1 = new Catapult\BaMLVerbRecord;
        $verb2 = new Catapult\BaMLVerbRecord;
        $verb4 = new Catapult\BaMLVerbRecord;
        $verb3 = new Catapult\BaMLVerbSpeakSentence;

        $verb->addVerb($verb1);
        $verb->addAttribute("voice", "male");


        $verb3->addVerb($verb2);
        $verb3->addAttribute("stuff", "1");
        $verb3->addAttribute("other", "1");
        $verb3->addAttribute("other", "1");

        $verb3->addText("test");
        $verb3->addText("test");
        
        $baml->add($verb3);
        echo $baml1;

        $baml3 = new Catapult\BaML;

        $baml3->getAsStream("./test.xml");

        echo var_dump($baml3);

        //echo var_dump($baml1);
        echo "\n";
        //echo $baml;
        
} catch (CatapultApiException $e) {
    echo var_dump($e);
}

?>
