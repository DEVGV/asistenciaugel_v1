import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::search
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:30
* @route '/api/trabajadores/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/api/trabajadores/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::search
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:30
* @route '/api/trabajadores/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::search
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:30
* @route '/api/trabajadores/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::search
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:30
* @route '/api/trabajadores/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::search
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:30
* @route '/api/trabajadores/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::search
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:30
* @route '/api/trabajadores/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::search
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:30
* @route '/api/trabajadores/search'
*/
searchForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

search.form = searchForm

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::usuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
export const usuario = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: usuario.url(args, options),
    method: 'get',
})

usuario.definition = {
    methods: ["get","head"],
    url: '/api/trabajadores/{trabajador}/usuario',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::usuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
usuario.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { trabajador: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { trabajador: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            trabajador: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        trabajador: typeof args.trabajador === 'object'
        ? args.trabajador.id
        : args.trabajador,
    }

    return usuario.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::usuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
usuario.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: usuario.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::usuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
usuario.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: usuario.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::usuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
const usuarioForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: usuario.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::usuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
usuarioForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: usuario.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\UsuarioApiController::usuario
* @see app/Http/Controllers/Configuracion/UsuarioApiController.php:21
* @route '/api/trabajadores/{trabajador}/usuario'
*/
usuarioForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: usuario.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

usuario.form = usuarioForm

const trabajadores = {
    search: Object.assign(search, search),
    usuario: Object.assign(usuario, usuario),
}

export default trabajadores