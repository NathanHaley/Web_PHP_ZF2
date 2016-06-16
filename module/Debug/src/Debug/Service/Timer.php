<?php 
namespace Debug\Service;

/** Measures time between start and stop calls.
 * @author nathan
 * 
 */

class Timer
{
    /**
     * Start times.
     * @var array
     */
    protected $start;
    
    /**
     * Float format switch/flag
     */
     protected $timeAsFloat;
     
     public function __construction($timeAsFloat=false)
     {
         $this->timeAsFloat = $timeAsFloat;
     }
     
     /**
      * Start timer
      * @param string $key
      */
     public function start($key)
     {
         $this->start[$key] = microtime($this->timeAsFloat);
     }
     
     /**
      * Stops timer
      * @param string $key
      * @return float|null the duration of the event
      */
     public function stop($key)
     {
         if (!isset($this->start[$key])) {
             return null;
         }
         
         return microtime($this->timeAsFloat) - $this->start[$key];
     }
}