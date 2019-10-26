<template>
    <div class="container">

        <div class="row col">
            <input type="text" @keyup="hit_enter" v-model="solrQuery" class="p-2" placeholder="Enter Query" size="55" />
            <button class="btn btn-primary" @click="search">Search</button>
        </div>

        <div class="row pt-3">

            <div class="col-sm-3" style="height:auto;overflow:auto;">
                <div v-for="(count, category) in facets">
                    <div style="float:left;">
                        {{ decoder(category, 30) }}
                    </div>
                    <div style="float:right;">
                        <span class="badge badge-pill badge-info font-weight-bold">{{ count }}</span>
                    </div>
                    <br style="clear:both;" />
                </div>
            </div>

            <div class="col-sm-9">
                <div v-for="doc in docs">
                    <div v-html="renderResult(doc)"></div>
                </div>
            </div>


        </div>
    </div>


</template>

<script>
    export default {
        data () {
            return {
                facets: {},
                docs: {},
                solrQuery: "",
            }
        },

        mounted() {
            this.search();
        },

        methods: {
            hit_enter(e){
                if (e.keyCode === 13) {
                    this.search();
                }else{
                    if(this.solrQuery.length > 3){
                        // Causes problems ?
                        //setTimeout(this.search,800);
                    }
                }
            },
            search(e) {
                axios.get("/api/search?query=" + this.solrQuery, "search", 15000)
                    .then(response => {
                        this.docs   = response.data.results;
                        this.facets = response.data.facets;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            decoder (str, maxLen) {
                var textArea = document.createElement('textarea');
                textArea.innerHTML = str;
                return (textArea.value.length > maxLen) ? textArea.value.substr(0,maxLen) + "..." : textArea.value;
            },
            renderResult(doc){
                if(doc.title !== undefined && doc.description !== undefined){
                    var html = "";

                    html += "<div  class='search-result'>";
                        html += "<span class='result-title'>";
                            html +="<a href='"+doc.uri+"' target='_blank'>"
                            html += (doc.highlight_title_text !== undefined && doc.highlight_title_text !== null && doc.highlight_title_text != '') ? doc.highlight_title_text : this.decoder(doc.title, 120);
                            html += "</a>";
                        html += "</span>";

                        html += "<div class='result-link'>";
                            html += (doc.highlight_uri_text !== undefined && doc.highlight_uri_text !== null && doc.highlight_uri_text != '') ? doc.highlight_uri_text : this.decoder(doc.uri, 120);
                        html += "</div>";


                        html += "<div>";

                            var hl = "";
                            if(doc.highlight_description_text !== undefined && doc.highlight_description_text !== null && doc.highlight_description_text != ''){
                                hl += doc.highlight_description_text + "... ";
                            }
                            if(doc.highlight_text_text !== undefined && doc.highlight_text_text !== null && doc.highlight_text_text != ''){
                                hl += doc.highlight_text_text + "... ";
                            }
                            if(doc.highlight_paragraphs !== undefined && doc.highlight_paragraphs !== null && doc.highlight_paragraphs != ''){
                                hl += doc.highlight_paragraphs + "... ";
                            }

                            html +="<span class='result-description'>"
                            html += (hl !== undefined && hl !== null && hl != '') ? hl : this.decoder(doc.description, 350);
                            html += "</span>";

                        html += "</div>";
                    html += "</div>";
                }


                return html;
            }

        }

    }
</script>
