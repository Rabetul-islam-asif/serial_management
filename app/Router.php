<?php

namespace App;

class Router {
    protected array $routes = [];
    protected array $middlewares = [];
    protected array $namedRoutes = [];

    // Middleware registry
    protected array $middlewareMap = [
        'auth' => \App\Middleware\AuthMiddleware::class,
        'role' => \App\Middleware\RoleMiddleware::class,
        'csrf' => \App\Middleware\CsrfMiddleware::class,
        'rate_limit' => \App\Middleware\RateLimitMiddleware::class,
    ];

    public function get(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        $this->addRoute('GET', $path, $handler, $middlewares, $name);
        return $this;
    }

    public function post(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        $this->addRoute('POST', $path, $handler, $middlewares, $name);
        return $this;
    }

    public function put(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        $this->addRoute('PUT', $path, $handler, $middlewares, $name);
        return $this;
    }

    public function delete(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        $this->addRoute('DELETE', $path, $handler, $middlewares, $name);
        return $this;
    }

    protected function addRoute(string $method, string $path, $handler, array $middlewares = [], ?string $name = null): void {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_\-]+)', $path);
        $pattern = '#^' . rtrim($pattern, '/') . '/?$#i';
        
        $route = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $middlewares,
            'name' => $name
        ];

        $this->routes[] = $route;
        
        if ($name) {
            $this->namedRoutes[$name] = $path;
        }
    }

    public function resolve(string $url, string $method) {
        $url = parse_url($url, PHP_URL_PATH);
        // Clean URL to handle bases (e.g. /doctor-serial/login -> /login)
        $baseUrl = parse_url(config('app.url', 'http://localhost/doctor-serial'), PHP_URL_PATH);
        if ($baseUrl && strpos($url, $baseUrl) === 0) {
            $url = substr($url, strlen($baseUrl));
        }
        $url = '/' . ltrim($url, '/');

        // Handle PUT/DELETE method override via POST parameter
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $url, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Execute Middlewares
                foreach ($route['middlewares'] as $middlewareConfig) {
                    $middlewareParts = explode(':', $middlewareConfig);
                    $middlewareName = $middlewareParts[0];
                    $middlewareArg = $middlewareParts[1] ?? null;
                    
                    if (isset($this->middlewareMap[$middlewareName])) {
                        $middlewareClass = $this->middlewareMap[$middlewareName];
                        $middlewareInstance = new $middlewareClass();
                        
                        if ($middlewareArg) {
                            $middlewareInstance->handle($middlewareArg);
                        } else {
                            $middlewareInstance->handle();
                        }
                    }
                }

                // Execute Handler
                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$controllerClass, $methodName] = $handler;
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $methodName)) {
                            return call_user_func_array([$controller, $methodName], $params);
                        }
                    }
                    throw new \Exception("Controller or method not found: {$controllerClass}@{$methodName}");
                }
                
                if (is_callable($handler)) {
                    return call_user_func_array($handler, $params);
                }
            }
        }

        // Return 404
        http_response_code(404);
        $this->renderError(404, "Page Not Found");
    }

    protected function renderError(int $code, string $message): void {
        $errorFile = dirname(__DIR__) . "/views/errors/{$code}.php";
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo "<h1 style='font-family: sans-serif; text-align: center; margin-top: 100px; color: #1e293b;'>Error {$code}: {$message}</h1>";
        }
        exit;
    }
}
