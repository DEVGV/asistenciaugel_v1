import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\ZonaController::index
* @see app/Http/Controllers/Configuracion/ZonaController.php:22
* @route '/zonas'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/zonas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::index
* @see app/Http/Controllers/Configuracion/ZonaController.php:22
* @route '/zonas'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::index
* @see app/Http/Controllers/Configuracion/ZonaController.php:22
* @route '/zonas'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::index
* @see app/Http/Controllers/Configuracion/ZonaController.php:22
* @route '/zonas'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::index
* @see app/Http/Controllers/Configuracion/ZonaController.php:22
* @route '/zonas'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::index
* @see app/Http/Controllers/Configuracion/ZonaController.php:22
* @route '/zonas'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::index
* @see app/Http/Controllers/Configuracion/ZonaController.php:22
* @route '/zonas'
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
* @see \App\Http\Controllers\Configuracion\ZonaController::store
* @see app/Http/Controllers/Configuracion/ZonaController.php:30
* @route '/zonas'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/zonas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::store
* @see app/Http/Controllers/Configuracion/ZonaController.php:30
* @route '/zonas'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::store
* @see app/Http/Controllers/Configuracion/ZonaController.php:30
* @route '/zonas'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::store
* @see app/Http/Controllers/Configuracion/ZonaController.php:30
* @route '/zonas'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::store
* @see app/Http/Controllers/Configuracion/ZonaController.php:30
* @route '/zonas'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::update
* @see app/Http/Controllers/Configuracion/ZonaController.php:38
* @route '/zonas/{zona}'
*/
export const update = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/zonas/{zona}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::update
* @see app/Http/Controllers/Configuracion/ZonaController.php:38
* @route '/zonas/{zona}'
*/
update.url = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { zona: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { zona: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            zona: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        zona: typeof args.zona === 'object'
        ? args.zona.id
        : args.zona,
    }

    return update.definition.url
            .replace('{zona}', parsedArgs.zona.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::update
* @see app/Http/Controllers/Configuracion/ZonaController.php:38
* @route '/zonas/{zona}'
*/
update.put = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::update
* @see app/Http/Controllers/Configuracion/ZonaController.php:38
* @route '/zonas/{zona}'
*/
update.patch = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::update
* @see app/Http/Controllers/Configuracion/ZonaController.php:38
* @route '/zonas/{zona}'
*/
const updateForm = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::update
* @see app/Http/Controllers/Configuracion/ZonaController.php:38
* @route '/zonas/{zona}'
*/
updateForm.put = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::update
* @see app/Http/Controllers/Configuracion/ZonaController.php:38
* @route '/zonas/{zona}'
*/
updateForm.patch = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Configuracion\ZonaController::destroy
* @see app/Http/Controllers/Configuracion/ZonaController.php:46
* @route '/zonas/{zona}'
*/
export const destroy = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/zonas/{zona}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::destroy
* @see app/Http/Controllers/Configuracion/ZonaController.php:46
* @route '/zonas/{zona}'
*/
destroy.url = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { zona: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { zona: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            zona: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        zona: typeof args.zona === 'object'
        ? args.zona.id
        : args.zona,
    }

    return destroy.definition.url
            .replace('{zona}', parsedArgs.zona.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::destroy
* @see app/Http/Controllers/Configuracion/ZonaController.php:46
* @route '/zonas/{zona}'
*/
destroy.delete = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::destroy
* @see app/Http/Controllers/Configuracion/ZonaController.php:46
* @route '/zonas/{zona}'
*/
const destroyForm = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::destroy
* @see app/Http/Controllers/Configuracion/ZonaController.php:46
* @route '/zonas/{zona}'
*/
destroyForm.delete = (args: { zona: string | number | { id: string | number } } | [zona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/api/zonas/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
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

const ZonaController = { index, store, update, destroy, search }

export default ZonaController