<?php
 
namespace frenzelgmbh\appcommon\nlpclassifier;
 
use \NlpTools\Classifiers\ClassifierInterface;
use \NlpTools\Documents\DocumentInterface;
 
class EndOfSentence implements ClassifierInterface
{
    /**
     * Splits up a certain text into a sentence array
     * @param  array    $classes [description]
     * @param  Document $d       [description]
     * @return [type]            [description]
     */
    public function classify(array $classes, DocumentInterface $d) {
        list($token,$before,$after) = $d->getDocumentData();
        
        $dotcnt = count(explode('.',$token))-1;
        $lastdot = substr($token,-1)=='.' OR substr($token,-1)=='?' OR substr($token,-1)=='!';
 
        if (!$lastdot) // assume that all sentences end in full stops
            return 'O';
 
        if ($dotcnt>1 && $dotcnt <3) // to catch some naive abbreviations U.S.A.
            return 'O';
 
        return 'EOW';
    }
}
