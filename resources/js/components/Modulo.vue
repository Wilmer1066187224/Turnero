<template>

<div style="text-align:center;margin-top:50px">

<h1>Módulo {{ modulo }}</h1>

<button @click="llamar">
Llamar siguiente turno
</button>

<p v-if="turno">

Atendiendo turno: {{ turno.numero }}

</p>

</div>

</template>

<script setup>

import axios from "axios"
import {ref} from "vue"

const turno = ref(null)

const modulo = window.location.pathname.split("/").pop()

const llamar = async()=>{

let res = await axios.post('/api/llamar/'+modulo)

turno.value = res.data

}

</script>