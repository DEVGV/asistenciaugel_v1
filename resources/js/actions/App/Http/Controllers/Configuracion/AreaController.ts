import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\AreaController::index
* @see app/Http/Controllers/Configuracion/AreaController.php:21
* @route '/areas'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/areas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\AreaController::index
* @see app/Http/Controllers/Configuracion/AreaController.php:21
* @route '/areas'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\AreaController::index
* @see app/Http/Controllers/Configuracion/AreaController.php:21
* @route '/areas'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::index
* @see app/Http/Controllers/Configuracion/AreaController.php:21
* @route '/areas'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::index
* @see app/Http/Controllers/Configuracion/AreaController.php:21
* @route '/areas'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::index
* @see app/Http/Controllers/Configuracion/AreaController.php:21
* @route '/areas'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::index
* @see app/Http/Controllers/Configuracion/AreaController.php:21
* @route '/areas'
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
* @see \App\Http\Controllers\Configuracion\AreaController::store
* @see app/Http/Controllers/Configuracion/AreaController.php:29
* @route '/areas'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/areas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\AreaController::store
* @see app/Http/Controllers/Configuracion/AreaController.php:29
* @route '/areas'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\AreaController::store
* @see app/Http/Controllers/Configuracion/AreaController.php:29
* @route '/areas'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::store
* @see app/Http/Controllers/Configuracion/AreaController.php:29
* @route '/areas'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::store
* @see app/Http/Controllers/Configuracion/AreaController.php:29
* @route '/areas'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Configuracion\AreaController::update
* @see app/Http/Controllers/Configuracion/AreaController.php:37
* @route '/areas/{area}'
*/
export const update = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/areas/{area}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Configuracion\AreaController::update
* @see app/Http/Controllers/Configuracion/AreaController.php:37
* @route '/areas/{area}'
*/
update.url = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { area: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { area: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            area: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        area: typeof args.area === 'object'
        ? args.area.id
        : args.area,
    }

    return update.definition.url
            .replace('{area}', parsedArgs.area.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\AreaController::update
* @see app/Http/Controllers/Configuracion/AreaController.php:37
* @route '/areas/{area}'
*/
update.put = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::update
* @see app/Http/Controllers/Configuracion/AreaController.php:37
* @route '/areas/{area}'
*/
update.patch = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::update
* @see app/Http/Controllers/Configuracion/AreaController.php:37
* @route '/areas/{area}'
*/
const updateForm = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::update
* @see app/Http/Controllers/Configuracion/AreaController.php:37
* @route '/areas/{area}'
*/
updateForm.put = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::update
* @see app/Http/Controllers/Configuracion/AreaController.php:37
* @route '/areas/{area}'
*/
updateForm.patch = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Configuracion\AreaController::destroy
* @see app/Http/Controllers/Configuracion/AreaController.php:45
* @route '/areas/{area}'
*/
export const destroy = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/areas/{area}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Configuracion\AreaController::destroy
* @see app/Http/Controllers/Configuracion/AreaController.php:45
* @route '/areas/{area}'
*/
destroy.url = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { area: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { area: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            area: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        area: typeof args.area === 'object'
        ? args.area.id
        : args.area,
    }

    return destroy.definition.url
            .replace('{area}', parsedArgs.area.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\AreaController::destroy
* @see app/Http/Controllers/Configuracion/AreaController.php:45
* @route '/areas/{area}'
*/
destroy.delete = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::destroy
* @see app/Http/Controllers/Configuracion/AreaController.php:45
* @route '/areas/{area}'
*/
const destroyForm = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\AreaController::destroy
* @see app/Http/Controllers/Configuracion/AreaController.php:45
* @route '/areas/{area}'
*/
destroyForm.delete = (args: { area: string | number | { id: string | number } } | [area: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const AreaController = { index, store, update, destroy }

export default AreaController