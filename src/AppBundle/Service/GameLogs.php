<?php

namespace AppBundle\Service;

use AppBundle\Service\Fighter\FighterService;
use AppBundle\Entity\Fighter;
use AppBundle\Service\Fighter\Skill\Listing\Skill;

class GameLogs{

    private static $stats = [];
    private static $logs = [];

    public static function add( $log ){
        array_push(self::$logs,$log);
    }

    public static function get(){
        return self::$logs;
    }

    public static function addStats( $teams ){
        $c = count( self::$stats );
        foreach ( $teams as $team_type => $team ){
            self::$stats[$c][$team_type] = [
                'team' => $team_type,
                'fighters' => []
            ];
            /**
             * @var Fighter $fighter
             * @var Skill $skill
             */
            $k = 0;
            foreach ( $team as $fighter){
                self::$stats[$c][$team_type]['fighters'][$k] = [
                    'name' => $fighter->getName(),
                    'health' => $fighter->getHealth(),
                    'health_remained' => $fighter->getHealthRemained(),
                    'strength' => $fighter->getStrength(),
                    'defence' => $fighter->getDefence(),
                    'speed' => $fighter->getSpeed(),
                    'luck' => $fighter->getLuck(),
                    'skills' => []
                ];
                //@TODO
                foreach( $fighter->getSkills() as $skill ){
                    self::$stats[$c][$team_type]['fighters'][$k]['skills'][] = [
//                        'type' => $skill->getType(),
                        'name' => $skill->getName(),
//                        'chance' => $skill->getChance()
                    ];
                }
                $k++;
            }
        }
    }

    public static function getStats( $key = null ){
        if( $key === null ){
            return self::$stats;
        }
        return self::$stats[$key];
    }
}