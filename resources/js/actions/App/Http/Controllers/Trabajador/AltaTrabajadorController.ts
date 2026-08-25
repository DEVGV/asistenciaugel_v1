import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/altas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::index
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:22
* @route '/altas'
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
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
export const store = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/trabajadores/{trabajador}/altas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
store.url = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{trabajador}', parsedArgs.trabajador.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
store.post = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
const storeForm = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::store
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:30
* @route '/trabajadores/{trabajador}/altas'
*/
storeForm.post = (args: { trabajador: string | number | { id: string | number } } | [trabajador: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
export const update = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/altas/{alta}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
update.url = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { alta: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { alta: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            alta: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        alta: typeof args.alta === 'object'
        ? args.alta.id
        : args.alta,
    }

    return update.definition.url
            .replace('{alta}', parsedArgs.alta.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
update.put = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
update.patch = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
const updateForm = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
updateForm.put = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::update
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:37
* @route '/altas/{alta}'
*/
updateForm.patch = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::darBaja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
export const darBaja = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: darBaja.url(args, options),
    method: 'post',
})

darBaja.definition = {
    methods: ["post"],
    url: '/altas/{alta}/baja',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::darBaja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
darBaja.url = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { alta: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { alta: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            alta: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        alta: typeof args.alta === 'object'
        ? args.alta.id
        : args.alta,
    }

    return darBaja.definition.url
            .replace('{alta}', parsedArgs.alta.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::darBaja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
darBaja.post = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: darBaja.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::darBaja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
const darBajaForm = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darBaja.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::darBaja
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:52
* @route '/altas/{alta}/baja'
*/
darBajaForm.post = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darBaja.url(args, options),
    method: 'post',
})

darBaja.form = darBajaForm

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
export const destroy = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/altas/{alta}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
destroy.url = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { alta: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { alta: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            alta: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        alta: typeof args.alta === 'object'
        ? args.alta.id
        : args.alta,
    }

    return destroy.definition.url
            .replace('{alta}', parsedArgs.alta.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
destroy.delete = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
const destroyForm = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Trabajador\AltaTrabajadorController::destroy
* @see app/Http/Controllers/Trabajador/AltaTrabajadorController.php:45
* @route '/altas/{alta}'
*/
destroyForm.delete = (args: { alta: string | number | { id: string | number } } | [alta: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const AltaTrabajadorController = { index, store, update, darBaja, destroy }

export default AltaTrabajadorController