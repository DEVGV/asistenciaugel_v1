import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
import biometria from './biometria'
import marcaciones from './marcaciones'
import horario from './horario'
/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:28
* @route '/api/mobile/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: login.url(options),
    method: 'post',
})

login.definition = {
    methods: ["post"],
    url: '/api/mobile/login',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:28
* @route '/api/mobile/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:28
* @route '/api/mobile/login'
*/
login.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: login.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:28
* @route '/api/mobile/login'
*/
const loginForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: login.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::login
* @see app/Http/Controllers/Api/MobileController.php:28
* @route '/api/mobile/login'
*/
loginForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: login.url(options),
    method: 'post',
})

login.form = loginForm

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:83
* @route '/api/mobile/me'
*/
export const me = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: me.url(options),
    method: 'get',
})

me.definition = {
    methods: ["get","head"],
    url: '/api/mobile/me',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:83
* @route '/api/mobile/me'
*/
me.url = (options?: RouteQueryOptions) => {
    return me.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:83
* @route '/api/mobile/me'
*/
me.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: me.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:83
* @route '/api/mobile/me'
*/
me.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: me.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:83
* @route '/api/mobile/me'
*/
const meForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: me.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:83
* @route '/api/mobile/me'
*/
meForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: me.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::me
* @see app/Http/Controllers/Api/MobileController.php:83
* @route '/api/mobile/me'
*/
meForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: me.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

me.form = meForm

/**
* @see \App\Http\Controllers\Api\MobileController::config
* @see app/Http/Controllers/Api/MobileController.php:74
* @route '/api/mobile/config'
*/
export const config = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: config.url(options),
    method: 'get',
})

config.definition = {
    methods: ["get","head"],
    url: '/api/mobile/config',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\MobileController::config
* @see app/Http/Controllers/Api/MobileController.php:74
* @route '/api/mobile/config'
*/
config.url = (options?: RouteQueryOptions) => {
    return config.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::config
* @see app/Http/Controllers/Api/MobileController.php:74
* @route '/api/mobile/config'
*/
config.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: config.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::config
* @see app/Http/Controllers/Api/MobileController.php:74
* @route '/api/mobile/config'
*/
config.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: config.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\MobileController::config
* @see app/Http/Controllers/Api/MobileController.php:74
* @route '/api/mobile/config'
*/
const configForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: config.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::config
* @see app/Http/Controllers/Api/MobileController.php:74
* @route '/api/mobile/config'
*/
configForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: config.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::config
* @see app/Http/Controllers/Api/MobileController.php:74
* @route '/api/mobile/config'
*/
configForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: config.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

config.form = configForm

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:65
* @route '/api/mobile/logout'
*/
export const logout = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/api/mobile/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:65
* @route '/api/mobile/logout'
*/
logout.url = (options?: RouteQueryOptions) => {
    return logout.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:65
* @route '/api/mobile/logout'
*/
logout.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:65
* @route '/api/mobile/logout'
*/
const logoutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\MobileController::logout
* @see app/Http/Controllers/Api/MobileController.php:65
* @route '/api/mobile/logout'
*/
logoutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: logout.url(options),
    method: 'post',
})

logout.form = logoutForm

const mobile = {
    login: Object.assign(login, login),
    me: Object.assign(me, me),
    config: Object.assign(config, config),
    logout: Object.assign(logout, logout),
    biometria: Object.assign(biometria, biometria),
    marcaciones: Object.assign(marcaciones, marcaciones),
    horario: Object.assign(horario, horario),
}

export default mobile