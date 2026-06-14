import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\PerfilController::sync
* @see app/Http/Controllers/Configuracion/PerfilController.php:67
* @route '/perfiles/{perfil}/permisos'
*/
export const sync = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sync.url(args, options),
    method: 'post',
})

sync.definition = {
    methods: ["post"],
    url: '/perfiles/{perfil}/permisos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::sync
* @see app/Http/Controllers/Configuracion/PerfilController.php:67
* @route '/perfiles/{perfil}/permisos'
*/
sync.url = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { perfil: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { perfil: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            perfil: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        perfil: typeof args.perfil === 'object'
        ? args.perfil.id
        : args.perfil,
    }

    return sync.definition.url
            .replace('{perfil}', parsedArgs.perfil.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::sync
* @see app/Http/Controllers/Configuracion/PerfilController.php:67
* @route '/perfiles/{perfil}/permisos'
*/
sync.post = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sync.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::sync
* @see app/Http/Controllers/Configuracion/PerfilController.php:67
* @route '/perfiles/{perfil}/permisos'
*/
const syncForm = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sync.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::sync
* @see app/Http/Controllers/Configuracion/PerfilController.php:67
* @route '/perfiles/{perfil}/permisos'
*/
syncForm.post = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sync.url(args, options),
    method: 'post',
})

sync.form = syncForm

const permisos = {
    sync: Object.assign(sync, sync),
}

export default permisos