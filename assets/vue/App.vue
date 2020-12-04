<template>
  <div class="container">
    <h1>Récupérer les sous titres de vos vidéos YouTube</h1>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mt-2 mb-2" data-toggle="modal" data-target="#modalExplication">
        1 - Voir la vidéo explicative
    </button>
    <a href="https://www.youtube.com/" target="_blank" class="btn" style="background-color:#FF0101; color:white;">2 - Aller sur YouTube</a>
 
    <form class="form-inline mt-4" @submit.prevent="submitForm">
        <label class="my-1 mr-2" for="url">L'URL</label>
        <input type="text" id="url" class="form-control mb-2 mr-sm-2" v-model="url" placeholder="https://www.youtube.com/api/timedtext?v=5f5t99azIud&...">

        <button type="submit" class="btn btn-success" :disabled="btnActif">
            3 - Récupérer les sous-titres
        </button>
    </form>

    <p v-if="loading">Chargement ...</p>

    <div class="row">
        <div class="col">
             <textarea id="result" class="form-control" rows="15" v-model="text" placeholder="Vos sous-titres de vidéos apparaitront ici ..."></textarea>
             <button class="btn btn-primary mt-2" @click="copyText">4 - Copier le texte</button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalExplication" tabindex="-1" aria-labelledby="modalExplicationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SepQQ2vvQxM?rel=0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>

import axios from 'axios'

export default {
    name: "App",
    data(){
        return {
            url: "",
            text: "",
            loading: false
        }
    },
    computed:{
        btnActif: function(){
            return this.url.length > 0 ? false : true
        }
    },
    methods:{
        submitForm: function(){
            this.loading = true
            axios
            .get(window.location.origin+'/subtiltes?url='+encodeURIComponent(this.url))
            .then(response => {
                this.text = response.data.text
                this.loading = false
            })
        },
        copyText: function(){
            var copyText = document.querySelector("#result");
            copyText.select();
            document.execCommand("copy");
        }
    }
}
</script>

<style>

</style>