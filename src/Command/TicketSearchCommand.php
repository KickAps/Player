<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:ticket-search',
    description: 'Add a short description for your command',
)]
class TicketSearchCommand extends Command
{
    private HttpClientInterface $client;
    private MailerInterface $mailer;
    private string $logPath = __DIR__ . '/../../var/log/ticket_search.log';
    private string $stateFile = __DIR__ . '/../../var/data/today_check.json';
    private string $url = "https://reelax-tickets.com/e/n/festival-a-tout-bout-dchamp-2025/achat";

    // Nombre total de vérifications à faire dans la journée
    private int $dailyChecks = 4;

    public function __construct(HttpClientInterface $client, MailerInterface $mailer)
    {
        parent::__construct();
        $this->client = $client;
        $this->mailer = $mailer;
    }

   protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $checks = [];

        if (file_exists($this->stateFile)) {
            $json = file_get_contents($this->stateFile);
            $checks = json_decode($json, true);
        }

        $today = (new \DateTime())->format('Y-m-d');
        $now = (new \DateTime())->format('Y-m-d H:i:s');

        if ((int) date('G') < 9) {
            exit;
        }

        // On ne garde que les vérifications d’aujourd’hui
        $checks = array_filter($checks, fn($entry) => str_starts_with($entry, $today));

        if (count($checks) >= $this->dailyChecks) {
            $io->success("Nombre de vérifications déjà atteint pour aujourd'hui.");
            return Command::SUCCESS;
        }

        // Tirage aléatoire : 1 chance sur X pour exécuter maintenant (ex : 1 sur 6 si cron chaque 10 min)
        if (random_int(1, 6) !== 1) {
            $io->note("Pas le bon tirage cette fois, la commande s'arrête.");
            return Command::SUCCESS;
        }

        try {
            $response = $this->client->request('GET', 'https://api.scraperapi.com/', [
                'query' => [
                    'api_key' => '5ca63dffce3db10d0e7aad580ac3454c',
                    'url' => $this->url,
                    'render' => 'true'
                ]
            ]);


            $html = $response->getContent();
            $crawler = new Crawler($html);

            $elements = $crawler->filter('p[data-cy="available-tickets"]');

            if ($elements->count() === 0) {
                $this->log("⚠️ [$now] Élément p[data-cy='available-tickets'] introuvable !");
            } else {
                $text = trim($elements->text());

                if ($text === " All tickets have been sold for the moment... " || " Tous les billets ont été vendus pour le moment... ") {
                    $this->log("🔕 [$now] Aucun billet disponible");
                } else {
                    $this->log("✅ [$now] Billets disponibles");
                    
                    $email = (new Email())
                        ->from('noreply@player.kickaps.fr')
                        ->to('flo.berson06@gmail.com')
                        ->subject('🎟️ Billets disponibles !')
                        ->text("Des billets semblent être disponibles : " . $this->url);

                    $this->mailer->send($email);
                }
            }

            $checks[] = $now;
            file_put_contents($this->stateFile, json_encode($checks));

        } catch (\Throwable $e) {
            $this->log("❌ [$today] Erreur lors de la vérification : " . $e->getMessage());
        }

        $io->success('Vérification terminée.');
        return Command::SUCCESS;
    }

    private function log(string $message): void
    {
        file_put_contents($this->logPath, $message . PHP_EOL, FILE_APPEND);
    }
}
