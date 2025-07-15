<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('l5-swagger.documentations.' . $documentation . '.api.title') }}</title>

    <link rel="stylesheet" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}">
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}" sizes="16x16" />

    <style>
        html {
            box-sizing: border-box;
            overflow-y: scroll;
        }

        *, *:before, *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }
    </style>

    @if(config('l5-swagger.defaults.ui.display.dark_mode'))
    <style>
        body#dark-mode,
        #dark-mode .scheme-container {
            background: #1b1b1b;
        }

        #dark-mode .scheme-container,
        #dark-mode .opblock .opblock-section-header {
            box-shadow: 0 1px 2px 0 rgba(255, 255, 255, 0.15);
        }

        #dark-mode .title,
        #dark-mode p,
        #dark-mode .btn,
        #dark-mode h1, #dark-mode h2, #dark-mode h3 {
            color: #e7e7e7;
        }

        #dark-mode .opblock.opblock-post {
            background: rgba(73, 204, 144, .25);
        }

        #dark-mode .opblock.opblock-get {
            background: rgba(97, 175, 254, .25);
        }

        #dark-mode .opblock.opblock-put {
            background: rgba(252, 161, 48, .25);
        }

        #dark-mode .opblock.opblock-delete {
            background: rgba(249, 62, 62, .25);
        }
    </style>
    @endif
</head>

<body @if(config('l5-swagger.defaults.ui.display.dark_mode')) id="dark-mode" @endif>
    <div id="swagger-ui"></div>

    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>
    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"></script>
    <script>
        window.onload = function () {
            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',
                url: "{!! $urlToDocs !!}",
                operationsSorter: "{{ $operationsSorter ?? 'alpha' }}",
                configUrl: "{{ $configUrl ?? '' }}",
                validatorUrl: "{{ $validatorUrl ?? null }}",
                oauth2RedirectUrl: "{{ route('l5-swagger.' . $documentation . '.oauth2_callback', [], $useAbsolutePath) }}",
                
                requestInterceptor: (request) => {
                    const token = localStorage.getItem("token");
                    if (token) {
                        request.headers['Authorization'] = 'Bearer ' + token;
                    }
                    return request;
                },

                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],

                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],

                layout: "StandaloneLayout",
                docExpansion: "{{ config('l5-swagger.defaults.ui.display.doc_expansion', 'none') }}",
                deepLinking: true,
                filter: {{ config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' }},
                persistAuthorization: "{{ config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' }}"
            });

            window.ui = ui;

            @if(in_array('oauth2', array_column(config('l5-swagger.defaults.securityDefinitions.securitySchemes'), 'type')))
            ui.initOAuth({
                usePkceWithAuthorizationCodeGrant: {{ config('l5-swagger.defaults.ui.authorization.oauth2.use_pkce_with_authorization_code_grant') ? 'true' : 'false' }}
            });
            @endif
        };
    </script>
</body>

</html>