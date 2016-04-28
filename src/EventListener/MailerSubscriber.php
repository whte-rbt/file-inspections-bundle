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

use InvalidArgumentException;
use Swift_Mailer;
use Swift_Message;
use Swift_Mime_Message;
use Twig_Environment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig_TemplateInterface;
use WhteRbt\FileInspectionsBundle\EventListener\Event\InspectionEvent;
use WhteRbt\FileInspectionsBundle\Events;

class MailerSubscriber implements EventSubscriberInterface
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $mailerConfig;

    /**
     * Constructor.
     *
     * @param Swift_Mailer     $mailer
     * @param Twig_Environment $twig
     * @param array            $mailerConfig
     */
    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig, array $mailerConfig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
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
        if (!$this->isMailEnabled('success', $event->getInfoLevel())) {
            return;
        }

        /** @var Twig_TemplateInterface $template */
        $template = $this->twig->loadTemplate('WhteRbtFileInspectionsBundle::successMail.txt.twig');

        $subject = $template->renderBlock('subject', [
            'job' => $event->getJob(),
            'inspector' => $event->getInspector()->getName(),
        ]);

        $body = $template->renderBlock('body', [
            'job' => $event->getJob(),
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
        if (!$this->isMailEnabled('error', $event->getInfoLevel())) {
            return;
        }

        /** @var Twig_TemplateInterface $template */
        $template = $this->twig->loadTemplate('WhteRbtFileInspectionsBundle::errorMail.txt.twig');

        $subject = $template->renderBlock('subject', [
            'job' => $event->getJob(),
            'inspector' => $event->getInspector()->getName(),
        ]);

        $body = $template->renderBlock('body', [
            'job' => $event->getJob(),
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

    /**
     * Returns true if mail receipt is enabled.
     *
     * The configuration allows the parameter "info_level" to be "all", "success",
     * "error" or "none" for each job.
     *
     * @param string $status
     * @param bool   $infoLevel
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    protected function isMailEnabled($status, $infoLevel)
    {
        if (!in_array($status, ['success', 'error'])) {
            throw new InvalidArgumentException(sprintf('The argument $status ("%s") does not contain a valid value ("status", "error").', $status));
        }

        if ($infoLevel == 'all' || $infoLevel == $status) {
            return true;
        }

        return false;
    }
}
