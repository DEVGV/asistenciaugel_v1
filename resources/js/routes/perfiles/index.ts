import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import permisos from './permisos'
/**
* @see \App\Http\Controllers\Configuracion\PerfilController::index
* @see app/Http/Controllers/Configuracion/PerfilController.php:22
* @route '/perfiles'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/perfiles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::index
* @see app/Http/Controllers/Configuracion/PerfilController.php:22
* @route '/perfiles'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::index
* @see app/Http/Controllers/Configuracion/PerfilController.php:22
* @route '/perfiles'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::index
* @see app/Http/Controllers/Configuracion/PerfilController.php:22
* @route '/perfiles'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::index
* @see app/Http/Controllers/Configuracion/PerfilController.php:22
* @route '/perfiles'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::index
* @see app/Http/Controllers/Configuracion/PerfilController.php:22
* @route '/perfiles'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::index
* @see app/Http/Controllers/Configuracion/PerfilController.php:22
* @route '/perfiles'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::store
* @see app/Http/Controllers/Configuracion/PerfilController.php:35
* @route '/perfiles'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/perfiles',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::store
* @see app/Http/Controllers/Configuracion/PerfilController.php:35
* @route '/perfiles'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::store
* @see app/Http/Controllers/Configuracion/PerfilController.php:35
* @route '/perfiles'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::store
* @see app/Http/Controllers/Configuracion/PerfilController.php:35
* @route '/perfiles'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::store
* @see app/Http/Controllers/Configuracion/PerfilController.php:35
* @route '/perfiles'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::update
* @see app/Http/Controllers/Configuracion/PerfilController.php:46
* @route '/perfiles/{perfil}'
*/
export const update = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/perfiles/{perfil}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::update
* @see app/Http/Controllers/Configuracion/PerfilController.php:46
* @route '/perfiles/{perfil}'
*/
update.url = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{perfil}', parsedArgs.perfil.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::update
* @see app/Http/Controllers/Configuracion/PerfilController.php:46
* @route '/perfiles/{perfil}'
*/
update.put = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::update
* @see app/Http/Controllers/Configuracion/PerfilController.php:46
* @route '/perfiles/{perfil}'
*/
update.patch = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::update
* @see app/Http/Controllers/Configuracion/PerfilController.php:46
* @route '/perfiles/{perfil}'
*/
const updateForm = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::update
* @see app/Http/Controllers/Configuracion/PerfilController.php:46
* @route '/perfiles/{perfil}'
*/
updateForm.put = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::update
* @see app/Http/Controllers/Configuracion/PerfilController.php:46
* @route '/perfiles/{perfil}'
*/
updateForm.patch = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::destroy
* @see app/Http/Controllers/Configuracion/PerfilController.php:59
* @route '/perfiles/{perfil}'
*/
export const destroy = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/perfiles/{perfil}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::destroy
* @see app/Http/Controllers/Configuracion/PerfilController.php:59
* @route '/perfiles/{perfil}'
*/
destroy.url = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{perfil}', parsedArgs.perfil.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::destroy
* @see app/Http/Controllers/Configuracion/PerfilController.php:59
* @route '/perfiles/{perfil}'
*/
destroy.delete = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::destroy
* @see app/Http/Controllers/Configuracion/PerfilController.php:59
* @route '/perfiles/{perfil}'
*/
const destroyForm = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\PerfilController::destroy
* @see app/Http/Controllers/Configuracion/PerfilController.php:59
* @route '/perfiles/{perfil}'
*/
destroyForm.delete = (args: { perfil: string | { id: string } } | [perfil: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const perfiles = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
    permisos: Object.assign(permisos, permisos),
}

export default perfiles