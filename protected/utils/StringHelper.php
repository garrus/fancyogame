<?php
class StringHelper {

    /**
     * Get a Roman number
     * $i should be between 1 to 16
     * 
     * @param int $i
     * @return string
     */
    public static function getRoman($i){
        
        $numbers = array(
            '',
            'I',     // 1
            'II',    // 2
            'III',   // 3
            'IV',    // 4
            'V',     // 5
            'VI',    // 6
            'VII',   // 7
            'VIII',  // 8
            'IX',    // 9
            'X',     // 10
            'XI',    // 11
            'XII',   // 12
            'XIII',  // 13
            'XIV',   // 14
            'XV',    // 15
            'XVI',   // 16
        );
        
        return $numbers[$i];
    }
    
    /**
     * Get a word of given number
     * $i should be between 1 to 26
     * 
     * @param int $i
     * @return string
     */
    public static function getWord($i){
        
        $words = array(
            '',
            'Alpha',
            'Bravo',            'Charlie',            'Delta',            'Echo',            'Foxtrot',            'Golf',            'Hotel',            'India',            'Juliet',            'Kilo',            'Lima',            'Mike',            'November',            'Oscar',            'Papa',
            'Quebec',
            'Romeo',
            'Sierra',
            'Tango',
            'Uniform',
            'Victor',
            'Whiskey',
            'Xray',
            'Yankee',
            'Zulu'
            );
        return $words[$i];
    }
    
    
}
