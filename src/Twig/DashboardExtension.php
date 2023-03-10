<?php

namespace App\Twig;

use App\Entity\Message;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class DashboardExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('GetNbMessageNotOpen', array($this, 'getNbMessageNotOpen')),
            new TwigFunction('GetNbMessagePROCESS', array($this, 'getNbMessagePROCESS')),

        ];
    }

    public function getNbMessageNotOpen( $messages): int
    {
        $count = 0;
        foreach ($messages as $message) {
            if ($message->getStatus() === Message::STATUS_NOT_OPEN) {
                ++$count;
            }
        }
        return $count;
    }
    public function getNbMessagePROCESS( $messages): int
    {
        $count = 0;
        foreach ($messages as $message) {
            if ($message->getStatus() === Message::STATUS_PROCESS) {
                ++$count;
            }
        }
        return $count;
    }

}