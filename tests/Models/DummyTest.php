<?php

use PHPUnit\Framework\TestCase;

class DummyTest extends TestCase
{
    public function testDummy()
    {
        self::assertTrue(1 + 1 == 2);
    }



    /*public function testFormatElapsed()
    {
        $result = Formatter::formatElapsedDateTime('2016-01-01 23:15:00');
        self::assertEquals(' 1 janvier 2016, 23:15', $result);
    }*/

    public function testFormatElapsedSeconds()
    {
        $result = Formatter::formatElapsedDateTime('2016-01-01 23:15:10', '2016-01-01 23:15:20');
        self::assertEquals('Il y a 10 secondes', $result);
    }

    public function testFormatElapsedMinutes()
    {
        $result = Formatter::formatElapsedDateTime('2016-01-01 23:16:10', '2016-01-01 23:14:20');
        self::assertEquals('Il y a 1 minute', $result);
    }

    public function testEnglishElapsedMessage()
    {
        Configuration::set(['application' => ['locale' => 'en_CA']]);
        $result = Formatter::formatElapsedDateTime('2016-01-01 23:16:10', '2016-01-01 23:14:20');
        self::assertEquals('1 minute ago', $result);
        Configuration::set(null);
    }

    public function testFormatElapsedYesterday()
    {
        $result = Formatter::formatElapsedDateTime('2015-12-31 23:00:10', '2016-01-01 23:14:20');
        self::assertEquals('Hier 23:00', $result);
    }

    public function testFormatElapsedToday()
    {
        $result = Formatter::formatElapsedDateTime('2016-01-01 10:00:10', '2016-01-01 23:14:20');
        self::assertEquals('Aujourd\'hui 10:00', $result);
    }


    /*public static function formatElapsedDateTime($dateTime, $now = null)
    {
        if (!$dateTime instanceof \DateTime) {
            $dateTime = new \DateTime($dateTime);
        }
        if (is_null($now)) {
            $now = new \DateTime();
        }
        if (!$now instanceof \DateTime) {
            $now = new \DateTime($now);
        }
        $diff = $dateTime->diff($now);
        return self::getElapsedMessage($diff, $dateTime);
    }

    private static function getElapsedMessage($diff, $dateTime)
    {
        if ($diff->d == 0) {
            if ($diff->h == 0) {
                if ($diff->i == 0) {
                    return (self::isFrench())
                        ? self::getFrenchElapsedMessage($diff->s, 'seconde')
                        : self::getEnglishElapsedMessage($diff->s, 'second');
                }
                return (self::isFrench())
                    ? self::getFrenchElapsedMessage($diff->i, 'minute')
                    : self::getEnglishElapsedMessage($diff->i, 'minute');
            }
            return ((self::isFrench()) ? 'Aujourd\'hui ' : 'Today ') . self::formatTime($dateTime);
        } elseif ($diff->d == 1 && $diff->h == 0) {
            return ((self::isFrench()) ? 'Hier ' : 'Yesterday ') . self::formatTime($dateTime);
        }
        return self::formatDateTime($dateTime);
    }

    private static function getFrenchElapsedMessage($delay, $word)
    {
        return "Il y a $delay $word" . (($delay > 1) ? 's' : '');
    }

    private static function getEnglishElapsedMessage($delay, $word)
    {
        return "$delay $word" . (($delay > 1) ? 's' : '') . ' ago';
    }*/

    /**
     * @return bool
     */
    /*private static function isFrench()
    {
        return strpos(Configuration::getApplicationConfiguration('locale'), 'fr') !== false;
    }*/
}