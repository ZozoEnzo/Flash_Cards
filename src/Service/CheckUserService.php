<?php

namespace App\Service;

use App\Repository\UserRepository;

class CheckUserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function checkUser(string $uuid)
    {
        return is_null($this->userRepository->findOneBy(['uuid' => $uuid]));

    }
}