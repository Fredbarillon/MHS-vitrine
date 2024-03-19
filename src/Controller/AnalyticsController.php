<?php

namespace App\Controller;

use Google_Client;
use Google_Service;
use Google_Service_Analytics;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnalyticsController extends AbstractController
{
    #[Route('/analytics', name: 'analytics')]
    public function analytics(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // Initialize Google Analytics API
        $analytics = $this->initializeAnalytics();

        // Retrieve first profile ID
        $profileId = $this->getFirstProfileId($analytics);

        // You can now use $analytics and $profileId to fetch analytics data
        // Example: $analytics->data_ga->get($profileId, ...);

        return $this->render('analytics/index.html.twig', [
            'profileId' => $profileId,
        ]);
    }

    private function initializeAnalytics(): Google_Service
    {
        // Path to the JSON credentials file
        $keyFileLocation = '/path/to/your/credentials.json';

        // Create and configure the Google_Client
        $client = new Google_Client();
        $client->setApplicationName("Hello Analytics Reporting");
        $client->setAuthConfig($keyFileLocation);
        $client->setScopes([Google_Service_Analytics::ANALYTICS_READONLY]);

        // Initialize Google_Service_Analytics
        return new Google_Service_Analytics($client);
    }

    private function getFirstProfileId(Google_Service_Analytics $analytics): string
    {
        // Retrieve the list of accounts
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Retrieve the list of properties
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Retrieve the list of views
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the ID of the first view
                    return $items[0]->getId();
                } else {
                    throw new \Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new \Exception('No properties found for this user.');
            }
        } else {
            throw new \Exception('No accounts found for this user.');
        }
    }
}
