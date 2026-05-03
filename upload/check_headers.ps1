$response = Invoke-WebRequest -Uri 'http://localhost:8000/index.php' -Method Head
$response.Headers['Set-Cookie']
