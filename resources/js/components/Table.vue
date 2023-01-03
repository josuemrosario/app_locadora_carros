<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key" >{{t.titulo}}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"></th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                   
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{valor}}</span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/' + valor" width="30" height="30">
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">
                            {{valor | formataDataTempoGlobal}}
                        </span>
                    </td>
                   
                    <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                        <button v-if="visualizar.visivel" 
                                :data-toggle="visualizar.dataToggle"  
                                :data-target="visualizar.dataTarget" 
                                class="btn btn-outline-primary btn-sm"
                                @click="setStore(obj)">
                        
                                Visualizar
                        
                        </button>
                        
                        <button v-if="atualizar.visivel" 
                                :data-toggle="atualizar.dataToggle"  
                                :data-target="atualizar.dataTarget"                        
                                class="btn btn-outline-primary btn-sm"
                                @click="setStore(obj)">
                                Atualizar
                        </button>
                        
                        <button v-if="remover.visivel" 
                                :data-toggle="remover.dataToggle"  
                                :data-target="remover.dataTarget"
                                class="btn btn-outline-danger btn-sm" 
                                @click="setStore(obj)">
                                Remover
                        </button>
                    </td>
                </tr>


                
                <!-- aula 367 -->
                <!-- comentado na aula 369
                <tr v-for="obj in dados" :key="obj.id">
                -->
                   <!-- comentado na aula 369
                    <td v-if="titulos.includes(chave)" v-for="valor, chave in obj" :key="chave">
                        <span v-if="chave == 'imagem'">
                            <img :src="'/storage/' + valor" width="30" height="30">
                        </span>
                        <span v-else>
                            {{valor}}
                        </span>
                    </td>
                    -->
                    
                    <!--
                    <th scope="row">{{m.id}}</th>
                    <td>{{m.nome}}</td>
                    <td> <img :src="'/storage/'+m.imagem" width="30" height="30">  </td>
                    -->
                <!-- comentado na aula 369
                </tr>
                -->
                
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {
        props: ['dados' , 'titulos', 'atualizar', 'visualizar', 'remover'],
        methods:{
            setStore(obj){
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.transacao.dados = ''
                this.$store.state.item = obj
            }
        },
        computed:{
            dadosFiltrados(){

                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []
                //console.log(this.campos)
                //console.log(this.dados)

                this.dados.map((item,chave) =>{
                    //console.log(chave, item)

                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        //console.log(campo)
                        itemFiltrado[campo] = item[campo]
                        
                    })
                    
                    dadosFiltrados.push(itemFiltrado)

                })
                return dadosFiltrados
            }
        }
    }
</script>
