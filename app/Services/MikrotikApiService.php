<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Stmt\TryCatch;

class MikrotikApiService
{
    public function connect($host, $username, $password, $port)
    {
        try {
            $client = new Client([
                'host' => $host,
                'user' => $username,
                'pass' => $password,
                'port' => (int) $port, // Change to 8729 for SSL, 8728 for Default
            ]);
            // dd($client);
            return $client;
        } catch (\Exception $e) {
            // dd($host, $username, $password, $port, $e->getMessage());
            // dd($e->getMessage());
            // return false;
            Log::error('Failed to connect to MikroTik router: ' . $e->getMessage(), [
                'host' => $host,
                'port' => $port
            ]);
        }
    }

    public function getInterfaces(Client $client)
    {
        if (!$client) {
            return null;
        }

        try {
            $query = new Query('/interface/print');
            $responses = $client->query($query)->read();

            return $responses;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getConnectedDevices($client)
    {
        return $client->query('/ip/arp/print')->read();
    }

    public function getDhcpLeases($client)
    {
        return $client->query('/ip/dhcp-server/lease/print')->read();
        // $leases = $client->query('/ip/dhcp-server/lease/print')->read();
        // return array_slice($leases, 0, 10); // Return only the first 10
    }

    public function getBoundLease($client)
    {
        try {
            
            $leases = $client->query('/ip/dhcp-server/lease/print')->read();
        
            // Filter leases where the 'status' is 'bound'
            $boundLeases = array_filter($leases, function ($lease) {
                return isset($lease['status']) && $lease['status'] === 'bound';
            });
            // dd($boundLeases);

        } catch (\Throwable $th) {
            // dump($th);
            return 0;
        }
        return count($boundLeases);
    }

    
    public function getFirewallList($client)
    {
        // Send query to RouterOS and parse response
        $response = $client->query('/ip/firewall/address-list/print')->read();
        return $response;
    }

    public function getPPP($client)
    {
        // Send query to RouterOS and parse response
        $response = $client->query('/ppp/active/print')->read();
        return $response;
    }

    public function getPPPSecrets($client)
    {
        // Send query to RouterOS and parse response
        $response = $client->query('/ppp/secret/print')->read();
        return $response;
    }
    
    public function getTraffic($client, $interface)
    {
        // Build the query in shorthand format
        $query = (new Query('/interface/monitor-traffic'))
            ->equal('interface', $interface)
            ->equal('once');

        // Send query and read the response
        $response = $client->query($query)->read();
        // dd($response);

        if (!empty($response[0])) {
            return response()->json([
                'rx' => (int)$response[0]['rx-bits-per-second'] / 1028,
                'tx' => (int)$response[0]['tx-bits-per-second'] / 1028,
                'time' => now()->format('H:i:s'),
            ]);
        }

        return response()->json(['error' => 'No data'], 500);
    }

    public function makeStatic($client, $leaseId, $comment)
    {
        try {
            // Set the lease to static
            // $leases = $client->query('/ip/dhcp-server/lease/print')->read();

            // $filteredLeases = array_map(fn($lease) => [
            //     'id' => $lease['.id'] ?? null,
            //     'dynamic' => $lease['dynamic'] ?? null
            // ], $leases);
            // dd($filteredLeases);

            // if (empty($lease)) {
            //     return response()->json(['error' => 'Lease ID not found'], 404);
            // }

            // Step 1: Make the lease static
            $query1 = new Query([
                '/ip/dhcp-server/lease/make-static',
                '=.id=' . $leaseId,  // Dynamically set the lease ID
            ]);

            // Execute the first query
            $response1 = $client->query($query1)->read();

            // Step 2: Set the comment separately
            $query2 = new Query([
                '/ip/dhcp-server/lease/set',
                '=.id=' . $leaseId,  
                '=comment=' . $comment, // Insert comment after making static
            ]);

            // Execute the second query
            $response2 = $client->query($query2)->read();
            // dd($response1, $response2);

            return "1";
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function removeStatic($client, $leaseId)
    {
        try {
            // Set the lease to static
            $query = new Query([
                '/ip/dhcp-server/lease/remove',
                '=.id=' . $leaseId,  // Dynamically set the lease ID
            ]);            

            $client->query($query)->read();
    
            return "1";
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function addOrUpdateFirewallList($client, $ip, $targetList, $user)
    {

        try {
            // Step 1: Check if the IP exists in the firewall
            $queryCheck = (new Query('/ip/firewall/address-list/print'))
            ->add('?address=' . $ip); // Proper filtering for MikroTik API
            // dd($queryCheck);
                
            $existing = $client->query($queryCheck)->read();
            // dd($existing);
            $comment = $targetList . " - " . $user;
            // dd($ip, $targetList, $user, $comment);

            if (count($existing) > 0) {
                // dd("Exist");
                $id = $existing[0]['.id'];
                $currentList = $existing[0]['list'];
                // dd($currentList, $targetList);

                if (trim($currentList) === trim($targetList)) {
                    // IP is already in the correct list, do nothing
                    return response()->json(['message' => "IP $ip is already in the '$targetList' list."], 200);
                } else {
                    // Step 2: Move IP to a different list (update)
                    $queryUpdate = (new Query('/ip/firewall/address-list/set'))
                        ->equal('.id', $id)
                        ->equal('list', $targetList)
                        ->equal('comment', $comment); // Divisi - User

                    $client->query($queryUpdate)->read();

                    return response()->json(['message' => "IP $ip moved from '$currentList' to '$targetList'."]);
                }
            } else {
                // Step 3: Add the IP if it's not in any list
                $queryAdd = (new Query('/ip/firewall/address-list/add'))
                    ->equal('list', $targetList)
                    ->equal('address', $ip)
                    ->equal('comment', $comment); // Divisi - User

                $results = $client->query($queryAdd)->read();
                // dd($results);

                return response()->json(['message' => "IP $ip added to '$targetList' list."]);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeFromFirewallList($client, $firewallId)
    {
        try {
            // Step 1: Remove from MikroTik Firewall List using `.id`
            $queryDelete = (new Query('/ip/firewall/address-list/remove'))
                ->equal('.id', $firewallId);

            $client->query($queryDelete)->read();

            // // Step 2: Remove from database
            // $firewall = Firewall::find($idrec);
            // if ($firewall) {
            //     $firewall->delete();
            // }

            return response()->json(['message' => "Firewall rule with ID '$firewallId' removed from MikroTik and deleted from the database."]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
