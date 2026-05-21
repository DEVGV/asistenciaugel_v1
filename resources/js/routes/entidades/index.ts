import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Entidad\EntidadController::index
* @see app/Http/Controllers/Entidad/EntidadController.php:22
* @route '/entidades'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/entidades',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Entidad\EntidadController::index
* @see app/Http/Controllers/Entidad/EntidadController.php:22
* @route '/entidades'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Entidad\EntidadController::index
* @see app/Http/Controllers/Entidad/EntidadController.php:22
* @route '/entidades'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::index
* @see app/Http/Controllers/Entidad/EntidadController.php:22
* @route '/entidades'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::index
* @see app/Http/Controllers/Entidad/EntidadController.php:22
* @route '/entidades'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::index
* @see app/Http/Controllers/Entidad/EntidadController.php:22
* @route '/entidades'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::index
* @see app/Http/Controllers/Entidad/EntidadController.php:22
* @route '/entidades'
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
* @see \App\Http\Controllers\Entidad\EntidadController::store
* @see app/Http/Controllers/Entidad/EntidadController.php:30
* @route '/entidades'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/entidades',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Entidad\EntidadController::store
* @see app/Http/Controllers/Entidad/EntidadController.php:30
* @route '/entidades'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Entidad\EntidadController::store
* @see app/Http/Controllers/Entidad/EntidadController.php:30
* @route '/entidades'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::store
* @see app/Http/Controllers/Entidad/EntidadController.php:30
* @route '/entidades'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::store
* @see app/Http/Controllers/Entidad/EntidadController.php:30
* @route '/entidades'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Entidad\EntidadController::update
* @see app/Http/Controllers/Entidad/EntidadController.php:38
* @route '/entidades/{entidade}'
*/
export const update = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/entidades/{entidade}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Entidad\EntidadController::update
* @see app/Http/Controllers/Entidad/EntidadController.php:38
* @route '/entidades/{entidade}'
*/
update.url = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { entidade: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { entidade: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            entidade: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        entidade: typeof args.entidade === 'object'
        ? args.entidade.id
        : args.entidade,
    }

    return update.definition.url
            .replace('{entidade}', parsedArgs.entidade.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Entidad\EntidadController::update
* @see app/Http/Controllers/Entidad/EntidadController.php:38
* @route '/entidades/{entidade}'
*/
update.put = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::update
* @see app/Http/Controllers/Entidad/EntidadController.php:38
* @route '/entidades/{entidade}'
*/
update.patch = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::update
* @see app/Http/Controllers/Entidad/EntidadController.php:38
* @route '/entidades/{entidade}'
*/
const updateForm = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::update
* @see app/Http/Controllers/Entidad/EntidadController.php:38
* @route '/entidades/{entidade}'
*/
updateForm.put = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::update
* @see app/Http/Controllers/Entidad/EntidadController.php:38
* @route '/entidades/{entidade}'
*/
updateForm.patch = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Entidad\EntidadController::destroy
* @see app/Http/Controllers/Entidad/EntidadController.php:46
* @route '/entidades/{entidade}'
*/
export const destroy = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/entidades/{entidade}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Entidad\EntidadController::destroy
* @see app/Http/Controllers/Entidad/EntidadController.php:46
* @route '/entidades/{entidade}'
*/
destroy.url = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { entidade: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { entidade: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            entidade: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        entidade: typeof args.entidade === 'object'
        ? args.entidade.id
        : args.entidade,
    }

    return destroy.definition.url
            .replace('{entidade}', parsedArgs.entidade.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Entidad\EntidadController::destroy
* @see app/Http/Controllers/Entidad/EntidadController.php:46
* @route '/entidades/{entidade}'
*/
destroy.delete = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::destroy
* @see app/Http/Controllers/Entidad/EntidadController.php:46
* @route '/entidades/{entidade}'
*/
const destroyForm = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Entidad\EntidadController::destroy
* @see app/Http/Controllers/Entidad/EntidadController.php:46
* @route '/entidades/{entidade}'
*/
destroyForm.delete = (args: { entidade: string | number | { id: string | number } } | [entidade: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const entidades = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default entidades