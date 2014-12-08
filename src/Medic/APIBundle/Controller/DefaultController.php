<?php

namespace Medic\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/api/get/{hash}")
     */
    public function getAction($hash)
    {
      $em = $this->getDoctrine()->getManager();

      $now = new \DateTime();
      $from = new \DateTime($now->format('Y-m-d'));
      $from->modify('+1 day');
      $to = new\DateTime($from->format('Y-m-d'));
      $to->modify('+2 weeks');

      $query = $em->createQuery(
      'SELECT e
      FROM MedicDashboardBundle:Event e
      LEFT JOIN e.calendar c
      WHERE c.hash = :hash AND e.start <= :to AND e.end >= :from'
      )->setParameters(array(
        'hash' => $hash,
        'from' => $from,
        'to' => $to
      ));
      $events = $query->getResult();

      $query = $em->createQuery(
      'SELECT h
      FROM MedicDashboardBundle:Holiday h
      LEFT JOIN h.calendar c
      WHERE c.hash = :hash AND h.start <= :to AND h.end >= :from'
      )->setParameters(array(
        'hash' => $hash,
        'to' => $to,
        'from' => $from
      ));
      $holidays = $query->getResult();

      $query = $em->createQuery(
      'SELECT s.weekDay, s.hour, s.minutes
      FROM MedicDashboardBundle:Slot s
      LEFT JOIN s.calendar c
      WHERE c.hash = :hash'
      )->setParameters(array(
        'hash' => $hash
      ));
      $slots = $query->getResult();

      $slotsTree = array();
      foreach ($slots as $slot) {
        $slotsTree[$slot['weekDay'].'_'.$slot['hour'].'_'.$slot['minutes']] = 1;
      }

      $freeSlots = array();

      while ($from < $to) {
        $isHoliday = false;
        foreach ($holidays as $holiday) {
          if ($holiday->getStart() <= $from && $holiday->getEnd() >= $from) {
            $isHoliday = true;
          }
        }
        $isUnavailable = false;
        foreach ($events as $event) {
          if ($event->getStart() <= $from && $event->getEnd() > $from) {
            $isUnavailable = true;
          }
        }
        $week = $from->format('N');
        $hour = $from->format('G');
        $minutes = (int) $from->format('i');
        if (!$isUnavailable && !$isHoliday && isset($slotsTree[$week.'_'.$hour.'_'.$minutes])) {
          $freeSlots[] = $from->format('Y-m-d H:i');
        }
        $from->modify('+15 minutes');
      }


      $response = new JsonResponse();
      $response->setData(array(
        'data' => $freeSlots
      ));
      return $response;
    }
}
