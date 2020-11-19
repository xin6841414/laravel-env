<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 11-18 018
 * Time: 16:53
 */

namespace Xin6841414\LaravelEnv;


use Illuminate\Container\Container;
use Illuminate\Support\Str;

class LaravelEnv
{
    /**
     * env文件全部内容
     * @var array
     */
    private $contents;

    /**
     * env 文件有效内容
     * @var array
     */
    private $invalidContents;

    /**
     * 格式化的数组内容，方便展示编辑
     * @var array
     */
    private $formatData;

    public function __construct()
    {
        $this->getEnvInvalidContents();
        $this->getFormatData();
    }

    /**
     * 获取.env 文件路径
     * @return string
     */
    private function getEnvFilePath()
    {
        return Container::getInstance()->environmentPath() . DIRECTORY_SEPARATOR .
            Container::getInstance()->environmentFile();
    }

    /**
     * 获取env文件内容
     * @return array
     */
    private function getEnvContents()
    {
        if (!$this->contents) {
            $this->contents = file($this->getEnvFilePath(), FILE_IGNORE_NEW_LINES);
        }
        return $this->contents;
    }

    /**
     * 获取有效的env内容
     * @return array
     */
    private function getEnvInvalidContents()
    {
        if (!$this->invalidContents) {
            $invalidContents = [];
            $contents = $this->getEnvContents();
            if (is_array($contents)) {
                foreach($contents as $n => $item) {
                    if (!empty($item) && ! Str::startsWith($item, '#')) {
                        $invalidContents[$n] = $item;
                    }
                }
                $this->invalidContents = $invalidContents;
            }
        }
        return $this->invalidContents;
    }

    /**
     * 获取格式化的配置信息列表
     * @return array
     */
    private function getFormatData()
    {
        if (!empty($this->formatData)) {
            return $this->formatData;
        }
        $data = [];
        foreach($this->invalidContents as $n => $v) {
            $temp = explode('=', $v, 2);
            if (count($temp) == 2) {
                $data[$n] = [
                    'num' => $n,
                    'key' => $temp[0],
                    'value' => $temp[1],
                ];
            }
        }
        $this->formatData = $data;
        return $data;
    }

    /**
     * 获取单个env配置的值
     * @param null $num 行号
     * @param null $key 键名
     * @return mixed|null
     */
    public function getEnv($num = null, $key = null)
    {
        if (empty($this->formatData)) {
            $this->getFormatData();
        }
        foreach($this->formatData as $n => $value) {
            if ($value['num'] === $num) {
                return $value['value'];
            }
            if ($value['key'] === $key) {
                return $value['value'];
            }
        }
        return null;
    }

    /**
     * 修改单个或多个配置项
     * @param array $data 格式： ['key1' => 'value1', 'key2' => 'value2']
     */
    public function setEnv(array $data)
    {
        $temp = $data;
        $i = 0;
        foreach($this->formatData as $key => &$value) {
            foreach($data as $k => $v) {
                if ($value['key'] == $k) {
                    unset($temp[$k]);
                    $value['value'] = $v;
                }
            }
            $i++;
        }

        if (!empty($temp)) {
            $addData = [];
            foreach($temp as $k1 => $v1) {
                $addData[$i+$k1] = [
                    'num' => $i+$k1,
                    'key' => $v1['key'],
                    'value' => $v1['value']
                ];
            }
            $this->formatData = array_merge($this->formatData, $addData);
        }
        $this->save();
    }

    /***
     * 保存配置
     */
    private function save()
    {
        $invalidContents = [];
        foreach($this->formatData as $k => $v) {
            $invalidContents[$v['num']] = $v['key'] . '='. $v['value'];
        }
        $this->invalidContents = $invalidContents;
        foreach($invalidContents as $n => $v) {
            if (!isset($this->contents[$n]) || $v !== $this->contents[$n]) {
                $this->contents[$n] = $v;
            }
        }
        $contentString = implode($this->contents, "\n");
        \File::put($this->getEnvFilePath(), $contentString);
    }
}