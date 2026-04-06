<template>

<div style="text-align:center;margin-top:100px">

<h1>Turno Actual</h1>

<h2 style="font-size:80px">

{{ turno.numero }}

</h2>

<h3>

Módulo {{ turno.modulo }}

</h3>

</div>

</template>

<script setup>

import axios from "axios"
import {ref,onMounted} from "vue"

const turno = ref({})

const cargar = async()=>{

let res = await axios.get('/api/turnos')

if(res.data.length){

turno.value = res.data[0]

}

}

onMounted(()=>{

cargar()

setInterval(cargar,3000)

})

</script>