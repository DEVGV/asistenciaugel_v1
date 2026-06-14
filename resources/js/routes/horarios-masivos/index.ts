import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Horario\HorarioMasivoController::store
* @see app/Http/Controllers/Horario/HorarioMasivoController.php:16
* @route '/horarios-masivos'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/horarios-masivos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Horario\HorarioMasivoController::store
* @see app/Http/Controllers/Horario/HorarioMasivoController.php:16
* @route '/horarios-masivos'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Horario\HorarioMasivoController::store
* @see app/Http/Controllers/Horario/HorarioMasivoController.php:16
* @route '/horarios-masivos'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioMasivoController::store
* @see app/Http/Controllers/Horario/HorarioMasivoController.php:16
* @route '/horarios-masivos'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Horario\HorarioMasivoController::store
* @see app/Http/Controllers/Horario/HorarioMasivoController.php:16
* @route '/horarios-masivos'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const horariosMasivos = {
    store: Object.assign(store, store),
}

export default horariosMasivos