import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::store
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:21
* @route '/instituciones/{institucione}/dias-no-laborables'
*/
export const store = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/instituciones/{institucione}/dias-no-laborables',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::store
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:21
* @route '/instituciones/{institucione}/dias-no-laborables'
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
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::store
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:21
* @route '/instituciones/{institucione}/dias-no-laborables'
*/
store.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::store
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:21
* @route '/instituciones/{institucione}/dias-no-laborables'
*/
const storeForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::store
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:21
* @route '/instituciones/{institucione}/dias-no-laborables'
*/
storeForm.post = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
export const update = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/dias-no-laborables/{diasNoLaborable}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
update.url = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { diasNoLaborable: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { diasNoLaborable: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            diasNoLaborable: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        diasNoLaborable: typeof args.diasNoLaborable === 'object'
        ? args.diasNoLaborable.id
        : args.diasNoLaborable,
    }

    return update.definition.url
            .replace('{diasNoLaborable}', parsedArgs.diasNoLaborable.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
update.put = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
update.patch = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
const updateForm = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
updateForm.put = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::update
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:29
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
updateForm.patch = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
export const destroy = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/dias-no-laborables/{diasNoLaborable}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
destroy.url = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { diasNoLaborable: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { diasNoLaborable: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            diasNoLaborable: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        diasNoLaborable: typeof args.diasNoLaborable === 'object'
        ? args.diasNoLaborable.id
        : args.diasNoLaborable,
    }

    return destroy.definition.url
            .replace('{diasNoLaborable}', parsedArgs.diasNoLaborable.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
destroy.delete = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
const destroyForm = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::destroy
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:37
* @route '/dias-no-laborables/{diasNoLaborable}'
*/
destroyForm.delete = (args: { diasNoLaborable: number | { id: number } } | [diasNoLaborable: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::generarFeriados
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:45
* @route '/instituciones/{institucione}/dias-no-laborables/generar-feriados'
*/
export const generarFeriados = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: generarFeriados.url(args, options),
    method: 'get',
})

generarFeriados.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/dias-no-laborables/generar-feriados',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::generarFeriados
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:45
* @route '/instituciones/{institucione}/dias-no-laborables/generar-feriados'
*/
generarFeriados.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return generarFeriados.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::generarFeriados
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:45
* @route '/instituciones/{institucione}/dias-no-laborables/generar-feriados'
*/
generarFeriados.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: generarFeriados.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::generarFeriados
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:45
* @route '/instituciones/{institucione}/dias-no-laborables/generar-feriados'
*/
generarFeriados.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: generarFeriados.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::generarFeriados
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:45
* @route '/instituciones/{institucione}/dias-no-laborables/generar-feriados'
*/
const generarFeriadosForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: generarFeriados.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::generarFeriados
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:45
* @route '/instituciones/{institucione}/dias-no-laborables/generar-feriados'
*/
generarFeriadosForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: generarFeriados.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\DiasNoLaborablesController::generarFeriados
* @see app/Http/Controllers/InstitucionEducativa/DiasNoLaborablesController.php:45
* @route '/instituciones/{institucione}/dias-no-laborables/generar-feriados'
*/
generarFeriadosForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: generarFeriados.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

generarFeriados.form = generarFeriadosForm

const DiasNoLaborablesController = { store, update, destroy, generarFeriados }

export default DiasNoLaborablesController