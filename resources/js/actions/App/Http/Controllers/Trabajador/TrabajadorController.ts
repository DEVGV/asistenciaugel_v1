import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:21
* @route '/trabajadores'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/trabajadores',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:21
* @route '/trabajadores'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:21
* @route '/trabajadores'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:21
* @route '/trabajadores'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:21
* @route '/trabajadores'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:21
* @route '/trabajadores'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::index
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:21
* @route '/trabajadores'
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
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:37
* @route '/trabajadores'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/trabajadores',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:37
* @route '/trabajadores'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:37
* @route '/trabajadores'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:37
* @route '/trabajadores'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::store
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:37
* @route '/trabajadores'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:45
* @route '/trabajadores/{trabajador}'
*/
export const show = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/trabajadores/{trabajador}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:45
* @route '/trabajadores/{trabajador}'
*/
show.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:45
* @route '/trabajadores/{trabajador}'
*/
show.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:45
* @route '/trabajadores/{trabajador}'
*/
show.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:45
* @route '/trabajadores/{trabajador}'
*/
const showForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:45
* @route '/trabajadores/{trabajador}'
*/
showForm.get = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::show
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:45
* @route '/trabajadores/{trabajador}'
*/
showForm.head = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:52
* @route '/trabajadores/{trabajador}'
*/
export const destroy = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/trabajadores/{trabajador}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:52
* @route '/trabajadores/{trabajador}'
*/
destroy.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:52
* @route '/trabajadores/{trabajador}'
*/
destroy.delete = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:52
* @route '/trabajadores/{trabajador}'
*/
const destroyForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\TrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/TrabajadorController.php:52
* @route '/trabajadores/{trabajador}'
*/
destroyForm.delete = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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

const TrabajadorController = { index, store, show, destroy, search }

export default TrabajadorController