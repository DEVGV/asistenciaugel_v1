import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
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

const cargas = {
    store: Object.assign(store, store),
}

export default cargas