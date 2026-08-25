import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::store
* @see app/Http/Controllers/Horario/CargaHorariaController.php:17
* @route '/horarios-cursos/{horarioCurso}/cargas'
*/
export const store = (args: { horarioCurso: string | number } | [horarioCurso: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/horarios-cursos/{horarioCurso}/cargas',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::store
* @see app/Http/Controllers/Horario/CargaHorariaController.php:17
* @route '/horarios-cursos/{horarioCurso}/cargas'
*/
store.url = (args: { horarioCurso: string | number } | [horarioCurso: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { horarioCurso: args }
    }

    if (Array.isArray(args)) {
        args = {
            horarioCurso: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        horarioCurso: args.horarioCurso,
    }

    return store.definition.url
            .replace('{horarioCurso}', parsedArgs.horarioCurso.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::store
* @see app/Http/Controllers/Horario/CargaHorariaController.php:17
* @route '/horarios-cursos/{horarioCurso}/cargas'
*/
store.post = (args: { horarioCurso: string | number } | [horarioCurso: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::store
* @see app/Http/Controllers/Horario/CargaHorariaController.php:17
* @route '/horarios-cursos/{horarioCurso}/cargas'
*/
const storeForm = (args: { horarioCurso: string | number } | [horarioCurso: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::store
* @see app/Http/Controllers/Horario/CargaHorariaController.php:17
* @route '/horarios-cursos/{horarioCurso}/cargas'
*/
storeForm.post = (args: { horarioCurso: string | number } | [horarioCurso: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::update
* @see app/Http/Controllers/Horario/CargaHorariaController.php:28
* @route '/cargas/{cargaHoraria}'
*/
export const update = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/cargas/{cargaHoraria}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::update
* @see app/Http/Controllers/Horario/CargaHorariaController.php:28
* @route '/cargas/{cargaHoraria}'
*/
update.url = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cargaHoraria: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { cargaHoraria: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            cargaHoraria: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        cargaHoraria: typeof args.cargaHoraria === 'object'
        ? args.cargaHoraria.id
        : args.cargaHoraria,
    }

    return update.definition.url
            .replace('{cargaHoraria}', parsedArgs.cargaHoraria.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::update
* @see app/Http/Controllers/Horario/CargaHorariaController.php:28
* @route '/cargas/{cargaHoraria}'
*/
update.put = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::update
* @see app/Http/Controllers/Horario/CargaHorariaController.php:28
* @route '/cargas/{cargaHoraria}'
*/
update.patch = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::update
* @see app/Http/Controllers/Horario/CargaHorariaController.php:28
* @route '/cargas/{cargaHoraria}'
*/
const updateForm = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::update
* @see app/Http/Controllers/Horario/CargaHorariaController.php:28
* @route '/cargas/{cargaHoraria}'
*/
updateForm.put = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::update
* @see app/Http/Controllers/Horario/CargaHorariaController.php:28
* @route '/cargas/{cargaHoraria}'
*/
updateForm.patch = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Horario\CargaHorariaController::destroy
* @see app/Http/Controllers/Horario/CargaHorariaController.php:39
* @route '/cargas/{cargaHoraria}'
*/
export const destroy = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/cargas/{cargaHoraria}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::destroy
* @see app/Http/Controllers/Horario/CargaHorariaController.php:39
* @route '/cargas/{cargaHoraria}'
*/
destroy.url = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cargaHoraria: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { cargaHoraria: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            cargaHoraria: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        cargaHoraria: typeof args.cargaHoraria === 'object'
        ? args.cargaHoraria.id
        : args.cargaHoraria,
    }

    return destroy.definition.url
            .replace('{cargaHoraria}', parsedArgs.cargaHoraria.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::destroy
* @see app/Http/Controllers/Horario/CargaHorariaController.php:39
* @route '/cargas/{cargaHoraria}'
*/
destroy.delete = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::destroy
* @see app/Http/Controllers/Horario/CargaHorariaController.php:39
* @route '/cargas/{cargaHoraria}'
*/
const destroyForm = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\CargaHorariaController::destroy
* @see app/Http/Controllers/Horario/CargaHorariaController.php:39
* @route '/cargas/{cargaHoraria}'
*/
destroyForm.delete = (args: { cargaHoraria: number | { id: number } } | [cargaHoraria: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const CargaHorariaController = { store, update, destroy }

export default CargaHorariaController