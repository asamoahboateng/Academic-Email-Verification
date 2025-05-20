<?php

namespace AcademicEmailVerifier;

class AcademicEmailVerifier
{
    private array $domains = [];
    private array $domainExceptions = [];
    private string $dataDirectory;

    public function __construct(string $dataDirectory = null)
    {
        $this->dataDirectory = $dataDirectory ?? __DIR__ . '/../data';
        $this->loadDomains();
    }

    private function loadDomains(): void
    {
        if (!is_dir($this->dataDirectory)) {
            throw new \RuntimeException("Data directory not found at: {$this->dataDirectory}");
        }

        $jsonFiles = glob($this->dataDirectory . '/*.json');
        
        if (empty($jsonFiles)) {
            throw new \RuntimeException("No JSON files found in: {$this->dataDirectory}");
        }

        foreach ($jsonFiles as $jsonFile) {
            $jsonContent = file_get_contents($jsonFile);
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException("Failed to parse JSON file: {$jsonFile}");
            }

            // Merge the domains from this file with existing domains
            $this->domains = array_merge($this->domains, $data);
        }
    }

    /**
     * Add a domain exception to be considered as academic
     * 
     * @param string $domain The domain to add (e.g., 'example.edu')
     * @param string $institutionName The name of the institution
     * @return void
     */
    public function addDomainException(string $domain, string $institutionName): void
    {
        $domain = strtolower($domain);
        $this->domainExceptions[$domain] = [
            'name' => $institutionName,
            'domains' => [$domain]
        ];
    }

    /**
     * Remove a domain exception
     * 
     * @param string $domain The domain to remove
     * @return void
     */
    public function removeDomainException(string $domain): void
    {
        $domain = strtolower($domain);
        unset($this->domainExceptions[$domain]);
    }

    /**
     * Get all domain exceptions
     * 
     * @return array List of domain exceptions
     */
    public function getDomainExceptions(): array
    {
        return $this->domainExceptions;
    }

    /**
     * Clear all domain exceptions
     * 
     * @return void
     */
    public function clearDomainExceptions(): void
    {
        $this->domainExceptions = [];
    }

    /**
     * Get the list of loaded data sources
     * 
     * @return array List of loaded JSON files
     */
    public function getLoadedSources(): array
    {
        return glob($this->dataDirectory . '/*.json');
    }

    /**
     * Validates if the given string is a valid email address
     * 
     * @param string $email The email address to validate
     * @return bool Returns true if the email is valid, false otherwise
     */
    public function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Verifies if the given email address belongs to an academic institution
     * 
     * @param string $email The email address to verify
     * @return array Returns an array containing verification results
     */
    public function verify(string $email): array
    {
        if (!$this->isValidEmail($email)) {
            return [
                'is_academic' => false,
                'university' => null,
                'error' => 'Invalid email format'
            ];
        }

        $domain = strtolower(explode('@', $email)[1]);

        // First check domain exceptions
        if (isset($this->domainExceptions[$domain])) {
            return [
                'is_academic' => true,
                'university' => $this->domainExceptions[$domain]['name'],
                'error' => null
            ];
        }

        // Then check the main database
        foreach ($this->domains as $university) {
            if (in_array($domain, $university['domains'])) {
                return [
                    'is_academic' => true,
                    'university' => $university['name'],
                    'error' => null
                ];
            }
        }

        return [
            'is_academic' => false,
            'university' => null,
            'error' => 'Domain not found in academic institutions database'
        ];
    }
}
