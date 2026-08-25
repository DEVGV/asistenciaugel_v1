import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
export const store = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/telefonos-ie',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
store.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { institucione: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { institucione: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            institucione: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        institucione: typeof args.institucione === 'object'
        ? args.institucione.id
        : args.institucione,
    }

    return store.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
store.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
const storeForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::store
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:18
* @route '/instituciones/{institucione}/telefonos-ie'
*/
storeForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:26
* @route '/telefonos-ie/{telefono}'
*/
export const update = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/telefonos-ie/{telefono}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:26
* @route '/telefonos-ie/{telefono}'
*/
update.url = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { telefono: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { telefono: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            telefono: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        telefono: typeof args.telefono === 'object'
        ? args.telefono.id
        : args.telefono,
    }

    return update.definition.url
            .replace('{telefono}', parsedArgs.telefono.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:26
* @route '/telefonos-ie/{telefono}'
*/
update.put = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:26
* @route '/telefonos-ie/{telefono}'
*/
update.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:26
* @route '/telefonos-ie/{telefono}'
*/
const updateForm = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:26
* @route '/telefonos-ie/{telefono}'
*/
updateForm.put = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::update
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:26
* @route '/telefonos-ie/{telefono}'
*/
updateForm.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:42
* @route '/telefonos-ie/{telefono}'
*/
export const destroy = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/telefonos-ie/{telefono}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:42
* @route '/telefonos-ie/{telefono}'
*/
destroy.url = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { telefono: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { telefono: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            telefono: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        telefono: typeof args.telefono === 'object'
        ? args.telefono.id
        : args.telefono,
    }

    return destroy.definition.url
            .replace('{telefono}', parsedArgs.telefono.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:42
* @route '/telefonos-ie/{telefono}'
*/
destroy.delete = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:42
* @route '/telefonos-ie/{telefono}'
*/
const destroyForm = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:42
* @route '/telefonos-ie/{telefono}'
*/
destroyForm.delete = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
export const darDeBaja = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

darDeBaja.definition = {
    methods: ["patch"],
    url: '/instituciones-telefonos/{telefono}/dar-de-baja',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
darDeBaja.url = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { telefono: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { telefono: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            telefono: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        telefono: typeof args.telefono === 'object'
        ? args.telefono.id
        : args.telefono,
    }

    return darDeBaja.definition.url
            .replace('{telefono}', parsedArgs.telefono.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
darDeBaja.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
const darDeBajaForm = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darDeBaja.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\TelefonoIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/TelefonoIEController.php:34
* @route '/instituciones-telefonos/{telefono}/dar-de-baja'
*/
darDeBajaForm.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darDeBaja.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

darDeBaja.form = darDeBajaForm

const TelefonoIEController = { store, update, destroy, darDeBaja }

export default TelefonoIEController