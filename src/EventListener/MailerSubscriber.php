<?php

/*
 * This file is part of the WhteRbtFileInspectionsBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FileInspectionsBundle\EventListener;

use Swift_Mailer;
use Swift_Message;
use Swift_Mime_Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Templating\EngineInterface;
use WhteRbt\FileInspectionsBundle\EventListener\Event\InspectionEvent;
use WhteRbt\FileInspectionsBundle\Events;

class MailerSubscriber implements EventSubscriberInterface
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var array
     */
    private $mailerConfig;

    /**
     * Constructor.
     *
     * @param Swift_Mailer    $mailer
     * @param EngineInterface $engine
     * @param array           $mailerConfig
     */
    public function __construct(Swift_Mailer $mailer, EngineInterface $engine, array $mailerConfig)
    {
        $this->mailer = $mailer;
        $this->engine = $engine;
        $this->mailerConfig = $mailerConfig;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::INSPECTION_SUCCESS => 'onSuccess',
            Events::INSPECTION_ERROR => 'onError',
        ];
    }

    /**
     * Sends message in a case of success.
     *
     * @param InspectionEvent $event
     */
    public function onSuccess(InspectionEvent $event)
    {
        $subject = sprintf('[%s: %s] Success Message',
            $event->getJobId(),
            $event->getInspector()->getName()
        );
        $body = $this->engine->render('WhteRbtFileInspectionsBundle::successMail.txt.twig', [
            'job' => $event->getJobId(),
            'inspector' => $event->getInspector()->getName(),
            'path' => $event->getInspector()->getPath(),
            'filename' => $event->getInspector()->getFilename(),
            'file' => $event->getInspector()->getFilenameWithPath(),
        ]);

        $this->mailer->send(
            $this->createMessage($subject, $body)
        );
    }

    /**
     * Sends message in a case of an error.
     *
     * @param InspectionEvent $event
     */
    public function onError(InspectionEvent $event)
    {
        $subject = sprintf('[%s: %s] Failure Notice',
            $event->getJobId(),
            $event->getInspector()->getName()
        );
        $body = $this->engine->render('WhteRbtFileInspectionsBundle::errorMail.txt.twig', [
            'job' => $event->getJobId(),
            'inspector' => $event->getInspector()->getName(),
            'path' => $event->getInspector()->getPath(),
            'filename' => $event->getInspector()->getFilename(),
            'file' => $event->getInspector()->getFilenameWithPath(),
        ]);

        $this->mailer->send(
            $this->createMessage($subject, $body)
        );
    }

    /**
     * Returns new Swift_Message.
     *
     * @param string $subject
     * @param string $body
     *
     * @return Swift_Mime_Message
     */
    protected function createMessage($subject, $body)
    {
        return Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->mailerConfig['sender'])
            ->setTo($this->mailerConfig['recipient'])
            ->setBody($body, 'text/plain');
    }
}
