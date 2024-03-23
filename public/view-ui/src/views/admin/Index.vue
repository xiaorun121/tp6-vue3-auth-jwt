<template>
  <Row>
    <Col span="24" style="height: 350px;">
        <Carousel
            v-model="value"
            :autoplay="setting.autoplay"
            :autoplay-speed="setting.autoplaySpeed"
            :dots="setting.dots"
            :radius-dot="setting.radiusDot"
            :trigger="setting.trigger"
            :arrow="setting.arrow">
            <CarouselItem v-for="(img,index) in verticalUrlList">
                <div class="demo-carouse{{ index+1 }}"><Image :src="img" width="100%" height="350px" lazy /></div>
            </CarouselItem>
        </Carousel>
    </Col>
  </Row>
  <br>
  
  <Row>
    <Col span="22" id="main1" style="width: 100%;height: 500px;">
    </Col>
  </Row>
  
</template>
<script lang="ts" setup>
  import * as echarts from 'echarts';

  import { onMounted } from 'vue';

  let value = 0;
  let setting = {
        autoplay: true,
        autoplaySpeed: 2000,
        dots: 'inside',
        radiusDot: true,
        trigger: 'click',
        arrow: 'hover'
    };

  let verticalUrlList = [
    'http://www.tp6.com/static/images/1.jpg',
    'http://www.tp6.com/static/images/2.jpg',
    'http://www.tp6.com/static/images/3.jpg',
    'http://www.tp6.com/static/images/4.jpg'
  ]

  onMounted(() => {
      init();
  });

  // 
  function init() {
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main1'), 'dark');

    var option;

    let data = genData(50);
    option = {
      title: {
        text: '同名数量统计',
        subtext: '纯属虚构',
        left: 'center'
      },
      tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b} : {c} ({d}%)'
      },
      legend: {
        type: 'scroll',
        orient: 'vertical',
        right: 10,
        top: 10,
        bottom: 10,
        data: data.legendData
      },
      series: [
        {
          name: '姓名',
          type: 'pie',
          radius: '55%',
          center: ['40%', '50%'],
          data: data.seriesData,
          emphasis: {
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
          }
        }
      ]
    };
    option && myChart.setOption(option);
  }

  function genData(count: number) {
    // prettier-ignore
    let nameList = [
      '赵', '钱', '孙', '李', '周', '吴', '郑', '王', '冯', '陈', '褚', '卫', '蒋', '沈', '韩', '杨', '朱', '秦', '尤', '许', '何', '吕', '施', '张', '孔', '曹', '严', '华', '金', '魏', '陶', '姜', '戚', '谢', '邹', '喻', '柏', '水', '窦', '章', '云', '苏', '潘', '葛', '奚', '范', '彭', '郎', '鲁', '韦', '昌', '马', '苗', '凤', '花', '方', '俞', '任', '袁', '柳', '酆', '鲍', '史', '唐', '费', '廉', '岑', '薛', '雷', '贺', '倪', '汤', '滕', '殷', '罗', '毕', '郝', '邬', '安', '常', '乐', '于', '时', '傅', '皮', '卞', '齐', '康', '伍', '余', '元', '卜', '顾', '孟', '平', '黄', '和', '穆', '萧', '尹', '姚', '邵', '湛', '汪', '祁', '毛', '禹', '狄', '米', '贝', '明', '臧', '计', '伏', '成', '戴', '谈', '宋', '茅', '庞', '熊', '纪', '舒', '屈', '项', '祝', '董', '梁', '杜', '阮', '蓝', '闵', '席', '季', '麻', '强', '贾', '路', '娄', '危'
    ];
    let legendData = [];
    let seriesData = [];
    for (var i = 0; i < count; i++) {
      var name =
        Math.random() > 0.65
          ? makeWord(4, 1) + '·' + makeWord(3, 0)
          : makeWord(2, 1);
      legendData.push(name);
      seriesData.push({
        name: name,
        value: Math.round(Math.random() * 100000)
      });
    }
    return {
      legendData: legendData,
      seriesData: seriesData
    };
    function makeWord(max: number, min: number) {
      let nameLen = Math.ceil(Math.random() * max + min);
      let name = [];
      for (var i = 0; i < nameLen; i++) {
        name.push(nameList[Math.round(Math.random() * nameList.length - 1)]);
      }
      return name.join('');
    }
  }


</script>