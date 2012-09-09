<?php

class Highchart
{
    protected $_id = 'chart';
    protected $_title = '';
    protected $_container = null;
    protected $_data = null;
    
    public function setId($id)
    {
        $this->_id = $id;
        
        return $this;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setTitle($title)
    {
        $this->_title = $title;
        
        return $this;
    }
    
    public function getTitle()
    {
        return $this->_title;
    }
    
    public function setContainer($container)
    {
        $this->_container = $container;
        
        return $this;
    }
    
    public function getContainer()
    {
        return $this->_container;
    }
    
    public function setData($data)
    {
        $this->_data = $data;
        
        return $this;
    }
    
    public function getData()
    {
        return $this->_data;
    }
    
    
    public function render()
    {
        $js_data = '';
        foreach($this->_data as $name => $value){
            $js_data .= '["'.$name.'",'.$value.'],';
        }
        $js_data = substr($js_data, 0, -1);
        
        $js = "
        <script>
        $(document).ready(function(){
            ".$this->_id." = new Highcharts.Chart({
                chart: {
                    renderTo: '".$this->_container."',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: '".$this->_title."'
                },
                tooltip: {
                    formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ this.y;
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>'+ this.point.name +'</b>: '+ this.y;
                            }
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Browser share',
                    data: [
                        ".$js_data."
                    ]
                }]
            });    
        });
        </script>
        ";
        $html = '<div id="'.$this->_container.'" style="width:1090px;"></div>';
        
        return $js.$html;
    }
}