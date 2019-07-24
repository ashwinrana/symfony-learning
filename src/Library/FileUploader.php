<?php


namespace App\Library;


use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function upload(UploadedFile $file)
    {
        $name = time().'-'. uniqid() . '.'. $file->guessClientExtension();
        $file->move( $this->container->getParameter('uploads_dir'), $name );
        return $name;
    }
}