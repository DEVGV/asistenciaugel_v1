import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Persona\DomicilioController::update
* @see app/Http/Controllers/Persona/DomicilioController.php:34
* @route '/domicilios/{domicilio}'
*/
export const update = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/domicilios/{domicilio}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Persona\DomicilioController::update
* @see app/Http/Controllers/Persona/DomicilioController.php:34
* @route '/domicilios/{domicilio}'
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
* @see \App\Http\Controllers\Persona\DomicilioController::update
* @see app/Http/Controllers/Persona/DomicilioController.php:34
* @route '/domicilios/{domicilio}'
*/
update.put = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Persona\DomicilioController::update
* @see app/Http/Controllers/Persona/DomicilioController.php:34
* @route '/domicilios/{domicilio}'
*/
update.patch = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Persona\DomicilioController::update
* @see app/Http/Controllers/Persona/DomicilioController.php:34
* @route '/domicilios/{domicilio}'
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
* @see \App\Http\Controllers\Persona\DomicilioController::update
* @see app/Http/Controllers/Persona/DomicilioController.php:34
* @route '/domicilios/{domicilio}'
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
* @see \App\Http\Controllers\Persona\DomicilioController::update
* @see app/Http/Controllers/Persona/DomicilioController.php:34
* @route '/domicilios/{domicilio}'
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
* @see \App\Http\Controllers\Persona\DomicilioController::destroy
* @see app/Http/Controllers/Persona/DomicilioController.php:48
* @route '/domicilios/{domicilio}'
*/
export const destroy = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/domicilios/{domicilio}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Persona\DomicilioController::destroy
* @see app/Http/Controllers/Persona/DomicilioController.php:48
* @route '/domicilios/{domicilio}'
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
* @see \App\Http\Controllers\Persona\DomicilioController::destroy
* @see app/Http/Controllers/Persona/DomicilioController.php:48
* @route '/domicilios/{domicilio}'
*/
destroy.delete = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Persona\DomicilioController::destroy
* @see app/Http/Controllers/Persona/DomicilioController.php:48
* @route '/domicilios/{domicilio}'
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
* @see \App\Http\Controllers\Persona\DomicilioController::destroy
* @see app/Http/Controllers/Persona/DomicilioController.php:48
* @route '/domicilios/{domicilio}'
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
* @see \App\Http\Controllers\Persona\DomicilioController::darDeBaja
* @see app/Http/Controllers/Persona/DomicilioController.php:41
* @route '/domicilios/{domicilio}/dar-de-baja'
*/
export const darDeBaja = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

darDeBaja.definition = {
    methods: ["patch"],
    url: '/domicilios/{domicilio}/dar-de-baja',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Persona\DomicilioController::darDeBaja
* @see app/Http/Controllers/Persona/DomicilioController.php:41
* @route '/domicilios/{domicilio}/dar-de-baja'
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
* @see \App\Http\Controllers\Persona\DomicilioController::darDeBaja
* @see app/Http/Controllers/Persona/DomicilioController.php:41
* @route '/domicilios/{domicilio}/dar-de-baja'
*/
darDeBaja.patch = (args: { domicilio: string | number | { id: string | number } } | [domicilio: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: darDeBaja.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Persona\DomicilioController::darDeBaja
* @see app/Http/Controllers/Persona/DomicilioController.php:41
* @route '/domicilios/{domicilio}/dar-de-baja'
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
* @see \App\Http\Controllers\Persona\DomicilioController::darDeBaja
* @see app/Http/Controllers/Persona/DomicilioController.php:41
* @route '/domicilios/{domicilio}/dar-de-baja'
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

const domicilios = {
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
    darDeBaja: Object.assign(darDeBaja, darDeBaja),
}

export default domicilios