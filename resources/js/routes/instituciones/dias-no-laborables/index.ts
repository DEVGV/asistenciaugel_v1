import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
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

const diasNoLaborables = {
    store: Object.assign(store, store),
    generarFeriados: Object.assign(generarFeriados, generarFeriados),
}

export default diasNoLaborables