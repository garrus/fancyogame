<?php
class TechTree {


    /**
     * Check the requirement and return error message
     *
     * @param ZPlanet $planet
     * @param $type
     * @param string $itemName
     * @return string  the error message. if the requirement
     * is matched, empty string will be returned.
     */
    public static function checkRequirement(ZPlanet $planet, $type, $itemName) {

        return '';
    }

    private static $_requirements = array(
        // Batiments
        12 => array(   3 =>   5, 113 =>   3),
        15 => array(  14 =>  10, 108 =>  10),
        21 => array(  14 =>   2),
        33 => array(  15 =>   1, 113 =>  12),

        // Batiments Lunaires
        42 => array(  41 =>   1),
        43 => array(  41 =>   1, 114 =>   7),

        // Technologies
        106 => array(  31 =>   3),
        108 => array(  31 =>   1),
        109 => array(  31 =>   4),
        110 => array( 113 =>   3,  31 =>   6),
        111 => array(  31 =>   2),
        113 => array(  31 =>   1),
        114 => array( 113 =>   5, 110 =>   5,  31 =>   7),
        115 => array( 113 =>   1,  31 =>   1),
        117 => array( 113 =>   1,  31 =>   2),
        118 => array( 114 =>   3,  31 =>   7),
        120 => array(  31 =>   1, 113 =>   2),
        121 => array(  31 =>   4, 120 =>   5, 113 =>   4),
        122 => array(  31 =>   5, 113 =>   8, 120 =>  10, 121 =>   5),
        123 => array(  31 =>  10, 108 =>   8, 114 =>   8),
        124 => array(  31 =>   3, 108 =>   4, 117 =>   3),
        199 => array(  31 =>  12),

        // Flotte
        202 => array(  21 =>   2, 115 =>   2),
        203 => array(  21 =>   4, 115 =>   6),
        204 => array(  21 =>   1, 115 =>   1),
        205 => array(  21 =>   3, 111 =>   2, 117 =>   2),
        206 => array(  21 =>   5, 117 =>   4, 121 =>   2),
        207 => array(  21 =>   7, 118 =>   4),
        208 => array(  21 =>   4, 117 =>   3),
        209 => array(  21 =>   4, 115 =>   6, 110 =>   2),
        210 => array(  21 =>   3, 115 =>   3, 106 =>   2),
        211 => array( 117 =>   6,  21 =>   8, 122 =>   5),
        212 => array(  21 =>   1),
        213 => array(  21 =>   9, 118 =>   6, 114 =>   5),
        214 => array(  21 =>  12, 118 =>   7, 114 =>   6, 199 =>   1),
        215 => array( 114 =>   5, 120 =>  12, 118 =>   5,  21 =>   8),

        // Defense
        401 => array(  21 =>   1),
        402 => array( 113 =>   1,  21 =>   2, 120 =>   3),
        403 => array( 113 =>   3,  21 =>   4, 120 =>   6),
        404 => array(  21 =>   6, 113 =>   6, 109 =>   3, 110 =>   1),
        405 => array(  21 =>   4, 121 =>   4),
        406 => array(  21 =>   8, 122 =>   7),
        407 => array( 110 =>   2,  21 =>   1),
        408 => array( 110 =>   6,  21 =>   6),
        502 => array(  44 =>   2),
        503 => array(  44 =>   4),

        // Officier
        603 => array( 601 =>   5),
        604 => array( 602 =>   5),
        605 => array( 601 =>  10, 603 =>   2),
        606 => array( 601 =>  10, 603 =>   2),
        607 => array( 605 =>   1),
        608 => array( 606 =>   1),
        609 => array( 601 =>  20, 603 =>  10, 605 =>   3, 606 =>   3, 607 =>   2, 608 =>   2),
        610 => array( 602 =>  10, 604 =>   5),
        611 => array( 602 =>  10, 604 =>   5),
        612 => array( 610 =>   1),
        613 => array( 611 =>   1),
        614 => array( 602 =>  20, 604 =>  10, 610 =>   2, 611 =>   2, 612 =>   1, 613 =>   3),
        615 => array( 614 =>   1, 609 =>   1),
    );
}
