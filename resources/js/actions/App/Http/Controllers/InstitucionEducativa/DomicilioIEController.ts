import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::store
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:18
* @route '/instituciones/{institucione}/domicilios-ie'
*/
export const store = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/domicilios-ie',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::store
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:18
* @route '/instituciones/{institucione}/domicilios-ie'
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
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::store
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:18
* @route '/instituciones/{institucione}/domicilios-ie'
*/
store.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::store
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:18
* @route '/instituciones/{institucione}/domicilios-ie'
*/
const storeForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::store
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:18
* @route '/instituciones/{institucione}/domicilios-ie'
*/
storeForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
export const update = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/domicilios-ie/{domicilio}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
update.url = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { domicilio: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { domicilio: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            domicilio: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        domicilio: typeof args.domicilio === 'object'
        ? args.domicilio.id
        : args.domicilio,
    }

    return update.definition.url
            .replace('{domicilio}', parsedArgs.domicilio.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
update.put = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
update.patch = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
const updateForm = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
updateForm.put = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::update
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:26
* @route '/domicilios-ie/{domicilio}'
*/
updateForm.patch = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
export const destroy = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/domicilios-ie/{domicilio}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
destroy.url = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { domicilio: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { domicilio: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            domicilio: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        domicilio: typeof args.domicilio === 'object'
        ? args.domicilio.id
        : args.domicilio,
    }

    return destroy.definition.url
            .replace('{domicilio}', parsedArgs.domicilio.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
destroy.delete = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
const destroyForm = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:42
* @route '/domicilios-ie/{domicilio}'
*/
destroyForm.delete = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:34
* @route '/instituciones-domicilios/{domicilio}/dar-de-baja'
*/
export const darDeBaja = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

darDeBaja.definition = {
    methods: ["patch"],
    url: '/instituciones-domicilios/{domicilio}/dar-de-baja',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:34
* @route '/instituciones-domicilios/{domicilio}/dar-de-baja'
*/
darDeBaja.url = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { domicilio: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { domicilio: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            domicilio: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        domicilio: typeof args.domicilio === 'object'
        ? args.domicilio.id
        : args.domicilio,
    }

    return darDeBaja.definition.url
            .replace('{domicilio}', parsedArgs.domicilio.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:34
* @route '/instituciones-domicilios/{domicilio}/dar-de-baja'
*/
darDeBaja.patch = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:34
* @route '/instituciones-domicilios/{domicilio}/dar-de-baja'
*/
const darDeBajaForm = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darDeBaja.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DomicilioIEController::darDeBaja
* @see app/Http/Controllers/InstitucionEducativa/DomicilioIEController.php:34
* @route '/instituciones-domicilios/{domicilio}/dar-de-baja'
*/
darDeBajaForm.patch = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: darDeBaja.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

darDeBaja.form = darDeBajaForm

const DomicilioIEController = { store, update, destroy, darDeBaja }

export default DomicilioIEController