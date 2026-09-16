<?php

class ForumController extends Controller {
    public const DISCORD_URL = 'https://discord.gg/JUZJhhkev';

    public function index(): void {
        $this->view('forum.index', [
            'pageTitle'        => 'Forum Laporan & Komunitas Diskusi - ' . APP_NAME,
            'discordInviteUrl' => self::DISCORD_URL,
        ]);
    }

    public function show(string|int $id): void {
        $this->redirect('forum');
    }

    public function create(): void {
        $this->redirect('forum');
    }
}
