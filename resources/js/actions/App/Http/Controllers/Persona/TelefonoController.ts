import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:27
* @route '/personas/{persona}/telefonos'
*/
export const store = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/personas/{persona}/telefonos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:27
* @route '/personas/{persona}/telefonos'
*/
store.url = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { persona: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { persona: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            persona: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        persona: typeof args.persona === 'object'
        ? args.persona.id
        : args.persona,
    }

    return store.definition.url
            .replace('{persona}', parsedArgs.persona.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:27
* @route '/personas/{persona}/telefonos'
*/
store.post = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:27
* @route '/personas/{persona}/telefonos'
*/
const storeForm = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::store
* @see app/Http/Controllers/Persona/TelefonoController.php:27
* @route '/personas/{persona}/telefonos'
*/
storeForm.post = (args: { persona: string | number | { id: string | number } } | [persona: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
*/
export const update = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/telefonos/{telefono}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
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
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
*/
update.put = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
*/
update.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
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
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
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
* @see \App\Http\Controllers\Persona\TelefonoController::update
* @see app/Http/Controllers/Persona/TelefonoController.php:34
* @route '/telefonos/{telefono}'
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
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:48
* @route '/telefonos/{telefono}'
*/
export const destroy = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/telefonos/{telefono}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:48
* @route '/telefonos/{telefono}'
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
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:48
* @route '/telefonos/{telefono}'
*/
destroy.delete = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:48
* @route '/telefonos/{telefono}'
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
* @see \App\Http\Controllers\Persona\TelefonoController::destroy
* @see app/Http/Controllers/Persona/TelefonoController.php:48
* @route '/telefonos/{telefono}'
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
* @see \App\Http\Controllers\Persona\TelefonoController::darDeBaja
* @see app/Http/Controllers/Persona/TelefonoController.php:41
* @route '/telefonos/{telefono}/dar-de-baja'
*/
export const darDeBaja = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

darDeBaja.definition = {
    methods: ["patch"],
    url: '/telefonos/{telefono}/dar-de-baja',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Persona\TelefonoController::darDeBaja
* @see app/Http/Controllers/Persona/TelefonoController.php:41
* @route '/telefonos/{telefono}/dar-de-baja'
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
* @see \App\Http\Controllers\Persona\TelefonoController::darDeBaja
* @see app/Http/Controllers/Persona/TelefonoController.php:41
* @route '/telefonos/{telefono}/dar-de-baja'
*/
darDeBaja.patch = (args: { telefono: string | number | { id: string | number } } | [telefono: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Persona\TelefonoController::darDeBaja
* @see app/Http/Controllers/Persona/TelefonoController.php:41
* @route '/telefonos/{telefono}/dar-de-baja'
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
* @see \App\Http\Controllers\Persona\TelefonoController::darDeBaja
* @see app/Http/Controllers/Persona/TelefonoController.php:41
* @route '/telefonos/{telefono}/dar-de-baja'
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

const TelefonoController = { store, update, destroy, darDeBaja }

export default TelefonoController